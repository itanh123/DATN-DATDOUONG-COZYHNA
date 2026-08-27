import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/cart_provider.dart';
import '../services/api_service.dart';

class CartScreen extends StatefulWidget {
  @override
  _CartScreenState createState() => _CartScreenState();
}

class _CartScreenState extends State<CartScreen> {
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _addressController = TextEditingController();
  bool _isSubmitting = false;

  void _checkout() {
    final cart = Provider.of<CartProvider>(context, listen: false);
    if (cart.items.isEmpty) return;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return Padding(
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(context).viewInsets.bottom,
                left: 16, right: 16, top: 16,
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Thông tin giao hàng', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  SizedBox(height: 16),
                  TextField(
                    controller: _nameController,
                    decoration: InputDecoration(labelText: 'Tên người nhận', border: OutlineInputBorder()),
                  ),
                  SizedBox(height: 12),
                  TextField(
                    controller: _phoneController,
                    decoration: InputDecoration(labelText: 'Số điện thoại', border: OutlineInputBorder()),
                    keyboardType: TextInputType.phone,
                  ),
                  SizedBox(height: 12),
                  TextField(
                    controller: _addressController,
                    decoration: InputDecoration(labelText: 'Địa chỉ cụ thể', border: OutlineInputBorder()),
                  ),
                  SizedBox(height: 24),
                  SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton(
                      onPressed: _isSubmitting ? null : () async {
                        if (_nameController.text.isEmpty || _phoneController.text.isEmpty || _addressController.text.isEmpty) {
                          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Vui lòng nhập đủ thông tin!')));
                          return;
                        }

                        setModalState(() => _isSubmitting = true);

                        // Build API Payload
                        List<Map<String, dynamic>> itemsPayload = cart.items.map((item) {
                          return {
                            'product_id': item.product.id,
                            'product_size_id': item.selectedSize.id,
                            'quantity': item.quantity,
                            'toppings': item.toppings.map((t) => {
                              'topping_id': t.topping.id,
                              'quantity': t.quantity
                            }).toList()
                          };
                        }).toList();

                        Map<String, dynamic> payload = {
                          'receiver_name': _nameController.text,
                          'receiver_phone': _phoneController.text,
                          'address': _addressController.text,
                          'province': 'Hà Nội',
                          'district': 'Cầu Giấy', // Default for now
                          'items': itemsPayload,
                          'payment_method': 'cash', // Default COD
                        };

                        try {
                          final response = await ApiService.post('/orders', payload, auth: true);
                          final data = json.decode(response.body);

                          if (response.statusCode == 201 && data['success']) {
                            cart.clear();
                            Navigator.of(context).pop(); // close modal
                            Navigator.of(context).pop(); // go back home
                            ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Đặt hàng thành công!')));
                          } else {
                            ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'Lỗi đặt hàng')));
                          }
                        } catch (e) {
                          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Đã xảy ra lỗi kết nối.')));
                        }
                        
                        setModalState(() => _isSubmitting = false);
                      },
                      style: ElevatedButton.styleFrom(backgroundColor: Theme.of(context).primaryColor),
                      child: _isSubmitting 
                          ? CircularProgressIndicator(color: Colors.white)
                          : Text('Xác nhận đặt hàng', style: TextStyle(color: Colors.white, fontSize: 16)),
                    ),
                  ),
                  SizedBox(height: 16),
                ],
              ),
            );
          }
        );
      }
    );
  }

  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context);
    final primaryColor = Theme.of(context).primaryColor;

    return Scaffold(
      appBar: AppBar(
        title: Text('Giỏ hàng'),
      ),
      body: cart.items.isEmpty
          ? Center(child: Text('Giỏ hàng của bạn đang trống'))
          : ListView.separated(
              padding: EdgeInsets.all(16),
              itemCount: cart.items.length,
              separatorBuilder: (_, __) => Divider(),
              itemBuilder: (context, index) {
                final item = cart.items[index];
                return Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Image placeholder or real image
                    Container(
                      width: 60, height: 60,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(8),
                        image: DecorationImage(
                          image: NetworkImage(item.product.imageUrl),
                          fit: BoxFit.cover,
                        ),
                      ),
                    ),
                    SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(item.product.name, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                          Text(item.selectedSize.sizeId == 1 ? 'Size S' : (item.selectedSize.sizeId == 2 ? 'Size M' : 'Size L'), style: TextStyle(color: Colors.grey[600], fontSize: 12)),
                          if (item.toppings.isNotEmpty)
                            Text(
                              item.toppings.map((e) => e.topping.name).join(', '),
                              style: TextStyle(color: Colors.grey[500], fontSize: 12),
                            ),
                          SizedBox(height: 8),
                          Text('${item.lineTotal.toStringAsFixed(0)}đ', style: TextStyle(fontWeight: FontWeight.bold, color: primaryColor)),
                        ],
                      ),
                    ),
                    Column(
                      children: [
                        IconButton(
                          icon: Icon(Icons.add_circle_outline),
                          onPressed: () => cart.updateQuantity(item, 1),
                        ),
                        Text('${item.quantity}'),
                        IconButton(
                          icon: Icon(Icons.remove_circle_outline),
                          onPressed: () => cart.updateQuantity(item, -1),
                        ),
                      ],
                    )
                  ],
                );
              },
            ),
      bottomNavigationBar: cart.items.isEmpty ? null : Container(
        padding: EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, -2))],
        ),
        child: SafeArea(
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Tổng cộng:', style: TextStyle(color: Colors.grey[600])),
                  Text('${cart.totalAmount.toStringAsFixed(0)}đ', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: primaryColor)),
                ],
              ),
              ElevatedButton(
                onPressed: _checkout,
                style: ElevatedButton.styleFrom(
                  backgroundColor: primaryColor,
                  foregroundColor: Colors.white,
                  padding: EdgeInsets.symmetric(horizontal: 32, vertical: 12),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                ),
                child: Text('Đặt hàng', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              )
            ],
          ),
        ),
      ),
    );
  }
}
