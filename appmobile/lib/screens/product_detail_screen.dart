import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../models/product.dart';
import '../models/topping.dart';
import '../models/cart_item.dart';
import '../models/review.dart';
import '../providers/cart_provider.dart';
import '../services/api_service.dart';

class ProductDetailScreen extends StatefulWidget {
  final Product product;
  
  const ProductDetailScreen({super.key, required this.product});

  @override
  _ProductDetailScreenState createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends State<ProductDetailScreen> {
  List<Topping> _availableToppings = [];
  bool _isLoadingToppings = true;

  ProductSize? _selectedSize;
  final List<CartItemTopping> _selectedToppings = [];
  int _quantity = 1;
  List<Review> _reviews = [];
  bool _isLoadingReviews = true;

  @override
  void initState() {
    super.initState();
    if (widget.product.sizes.isNotEmpty) {
      _selectedSize = widget.product.sizes[0]; // Default size
    }
    _fetchToppings();
    _fetchReviews();
  }

  Future<void> _fetchToppings() async {
    try {
      final res = await ApiService.get('/toppings');
      if (res.statusCode == 200) {
        final data = json.decode(res.body);
        setState(() {
          _availableToppings = (data['data'] as List)
              .map((e) => Topping.fromJson(e))
              .toList();
        });
      }
    } catch (e) {
      print(e);
    }
    setState(() => _isLoadingToppings = false);
  }

  Future<void> _fetchReviews() async {
    try {
      final response = await ApiService.get('/products/${widget.product.id}/reviews');
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success']) {
          setState(() {
            _reviews = (data['data'] as List).map((e) => Review.fromJson(e)).toList();
            _isLoadingReviews = false;
          });
        }
      }
    } catch (e) {
      setState(() {
        _isLoadingReviews = false;
      });
    }
  }

  void _toggleTopping(Topping topping, bool selected) {
    setState(() {
      if (selected) {
        _selectedToppings.add(CartItemTopping(topping: topping, quantity: 1));
      } else {
        _selectedToppings.removeWhere((item) => item.topping.id == topping.id);
      }
    });
  }

  double get _currentTotal {
    if (_selectedSize == null) return 0;
    double topTotal = 0;
    for (var t in _selectedToppings) {
      topTotal += t.topping.price;
    }
    return (_selectedSize!.sellingPrice + topTotal) * _quantity;
  }

  void _addToCart() {
    if (_selectedSize == null) return;
    
    Provider.of<CartProvider>(context, listen: false).addItem(
      widget.product, 
      _selectedSize!, 
      _selectedToppings, 
      _quantity
    );
    
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text('Đã thêm vào giỏ hàng!'), duration: Duration(seconds: 1)),
    );
    Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    final primaryColor = Theme.of(context).primaryColor;

    return Scaffold(
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 300,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              background: Image.network(widget.product.imageUrl, fit: BoxFit.cover),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    widget.product.name,
                    style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                  ),
                  SizedBox(height: 4),
                  Text(
                    (_selectedSize?.stock ?? widget.product.stock) > 0 
                        ? 'Còn hàng: ${_selectedSize?.stock ?? widget.product.stock}' 
                        : 'Hết hàng',
                    style: TextStyle(
                      color: (_selectedSize?.stock ?? widget.product.stock) > 0 ? Colors.green : Colors.red,
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  SizedBox(height: 16),
                  Text(
                    'Mô tả',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  SizedBox(height: 8),
                  Text(
                    widget.product.description ?? 'Không có mô tả',
                    style: TextStyle(fontSize: 16, color: Colors.grey[700], height: 1.5),
                  ),
                  SizedBox(height: 24),
                  
                  // Sizes
                  Text('Kích cỡ', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  SizedBox(height: 8),
                  Wrap(
                    spacing: 8,
                    children: widget.product.sizes.map((size) {
                      final isSelected = _selectedSize?.id == size.id;
                      return ChoiceChip(
                        label: Text(size.sizeId == 1 ? 'Size S' : (size.sizeId == 2 ? 'Size M' : 'Size L')),
                        selected: isSelected,
                        onSelected: (selected) {
                          if (selected) setState(() => _selectedSize = size);
                        },
                        selectedColor: primaryColor.withValues(alpha: 0.2),
                        labelStyle: TextStyle(
                          color: isSelected ? primaryColor : Colors.black87,
                          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal
                        ),
                      );
                    }).toList(),
                  ),
                  
                  SizedBox(height: 24),

                  // Toppings
                  Text('Thêm Topping', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  SizedBox(height: 8),
                  if (_isLoadingToppings) 
                    Center(child: CircularProgressIndicator())
                  else if (_availableToppings.isEmpty)
                    Text('Không có topping')
                  else
                    ..._availableToppings.map((topping) {
                      final isSelected = _selectedToppings.any((t) => t.topping.id == topping.id);
                      return CheckboxListTile(
                        title: Text(topping.name),
                        subtitle: Text('+${topping.price.toStringAsFixed(0)}đ'),
                        value: isSelected,
                        onChanged: (val) => _toggleTopping(topping, val ?? false),
                        activeColor: primaryColor,
                        contentPadding: EdgeInsets.zero,
                        controlAffinity: ListTileControlAffinity.leading,
                      );
                    }),

                  SizedBox(height: 24),
                  
                  // Reviews Section
                  Text(
                    'Đánh giá từ khách hàng (${_reviews.length})',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  SizedBox(height: 16),
                  _isLoadingReviews 
                    ? Center(child: CircularProgressIndicator()) 
                    : _reviews.isEmpty
                      ? Text('Chưa có đánh giá nào cho sản phẩm này.', style: TextStyle(fontStyle: FontStyle.italic, color: Colors.grey))
                      : ListView.separated(
                          shrinkWrap: true,
                          physics: NeverScrollableScrollPhysics(),
                          itemCount: _reviews.length,
                          separatorBuilder: (context, index) => Divider(),
                          itemBuilder: (context, index) {
                            final review = _reviews[index];
                            return Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    CircleAvatar(
                                      backgroundColor: Colors.grey[300],
                                      radius: 16,
                                      child: Icon(Icons.person, size: 20, color: Colors.grey[600]),
                                    ),
                                    SizedBox(width: 8),
                                    Expanded(
                                      child: Text(
                                        review.user?.name ?? 'Khách hàng',
                                        style: TextStyle(fontWeight: FontWeight.bold),
                                      ),
                                    ),
                                    Text(
                                      DateFormat('dd/MM/yyyy').format(DateTime.parse(review.createdAt)),
                                      style: TextStyle(color: Colors.grey, fontSize: 12),
                                    ),
                                  ],
                                ),
                                SizedBox(height: 4),
                                Row(
                                  children: List.generate(5, (starIndex) {
                                    return Icon(
                                      starIndex < review.rating ? Icons.star : Icons.star_border,
                                      color: Colors.amber,
                                      size: 16,
                                    );
                                  }),
                                ),
                                if (review.comment != null && review.comment!.isNotEmpty) ...[
                                  SizedBox(height: 8),
                                  Text(review.comment!),
                                ],
                                if (review.adminReply != null && review.adminReply!.isNotEmpty) ...[
                                  SizedBox(height: 8),
                                  Container(
                                    padding: EdgeInsets.all(8),
                                    decoration: BoxDecoration(
                                      color: Colors.grey[100],
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Row(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Icon(Icons.storefront, size: 16, color: primaryColor),
                                        SizedBox(width: 8),
                                        Expanded(
                                          child: Column(
                                            crossAxisAlignment: CrossAxisAlignment.start,
                                            children: [
                                              Text('Phản hồi từ quán', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: primaryColor)),
                                              SizedBox(height: 4),
                                              Text(review.adminReply!, style: TextStyle(fontSize: 13)),
                                            ],
                                          ),
                                        ),
                                      ],
                                    ),
                                  )
                                ],
                              ],
                            );
                          },
                        ),
                    
                  SizedBox(height: 100), // Space for bottom bar
                ],
              ),
            ),
          )
        ],
      ),
      bottomSheet: Container(
        padding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, -2))],
        ),
        child: SafeArea(
          child: Row(
            children: [
              // Quantity control
              Container(
                decoration: BoxDecoration(
                  border: Border.all(color: Colors.grey[300]!),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Row(
                  children: [
                    IconButton(
                      icon: Icon(Icons.remove),
                      onPressed: _quantity > 1 ? () => setState(() => _quantity--) : null,
                    ),
                    Text('$_quantity', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    IconButton(
                      icon: Icon(Icons.add),
                      onPressed: () => setState(() => _quantity++),
                    ),
                  ],
                ),
              ),
              SizedBox(width: 16),
              // Add to cart button
              Expanded(
                child: ElevatedButton(
                  onPressed: (_selectedSize?.stock ?? widget.product.stock) > 0 ? _addToCart : null,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: primaryColor,
                    foregroundColor: Colors.white,
                    disabledBackgroundColor: Colors.grey[400],
                    padding: EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                  ),
                  child: Text(
                    (_selectedSize?.stock ?? widget.product.stock) > 0 
                        ? 'Thêm - ${_currentTotal.toStringAsFixed(0)}đ' 
                        : 'Tạm hết hàng', 
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)
                  ),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
