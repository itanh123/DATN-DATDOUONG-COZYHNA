import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../models/category.dart';
import '../models/product.dart';
import '../services/api_service.dart';
import 'login_screen.dart';
import 'product_detail_screen.dart';
import 'cart_screen.dart';
import 'order_history_screen.dart';
import 'ai_chat_screen.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;
  List<Product> _allProducts = [];

  @override
  void initState() {
    super.initState();
    _fetchProducts();
  }

  Future<void> _fetchProducts() async {
    try {
      final res = await ApiService.get('/products');
      if (res.statusCode == 200) {
        final data = json.decode(res.body);
        setState(() {
          _allProducts = (data['data'] as List).map((e) => Product.fromJson(e)).toList();
        });
      }
    } catch (e) {
      print(e);
    }
  }

  @override
  Widget build(BuildContext context) {
    final primaryColor = Theme.of(context).primaryColor;

    final List<Widget> _pages = [
      _HomeTab(products: _allProducts),
      _MenuTab(),
      _ProfileTab(),
    ];

    return Scaffold(
      body: _pages[_currentIndex],
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        selectedItemColor: primaryColor,
        unselectedItemColor: Colors.grey,
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
        items: [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Trang chủ'),
          BottomNavigationBarItem(icon: Icon(Icons.restaurant_menu), label: 'Thực đơn'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Tài khoản'),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.push(context, MaterialPageRoute(builder: (_) => AiChatScreen()));
        },
        backgroundColor: primaryColor,
        child: Icon(Icons.smart_toy, color: Colors.white),
        tooltip: 'Trợ lý AI',
      ),
    );
  }
}

// ---------------- TAB 1: TRANG CHỦ ----------------
class _HomeTab extends StatelessWidget {
  final List<Product> products;

  _HomeTab({required this.products});

  @override
  Widget build(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;
    final primaryColor = Theme.of(context).primaryColor;
    
    return Scaffold(
      appBar: AppBar(
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Chào buổi sáng,', style: TextStyle(fontSize: 14, color: Colors.white70)),
            Text('${user?.username ?? "Khách"}!', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          ],
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.shopping_cart),
            onPressed: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => CartScreen()));
            },
          ),
        ],
      ),
      body: CustomScrollView(
        slivers: [
          SliverToBoxAdapter(
            child: Container(
              width: double.infinity,
              margin: EdgeInsets.all(16),
              padding: EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: Colors.green[500],
                borderRadius: BorderRadius.circular(16),
                image: DecorationImage(
                  image: NetworkImage('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=500&q=80'),
                  fit: BoxFit.cover,
                  colorFilter: ColorFilter.mode(Colors.black.withOpacity(0.4), BlendMode.darken),
                )
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Khám phá', style: TextStyle(color: Colors.white, fontSize: 16)),
                  SizedBox(height: 8),
                  Text('Hương vị mới\nmỗi ngày', style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                ],
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              child: Text('Gợi ý cho bạn', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            ),
          ),
          SliverPadding(
            padding: EdgeInsets.symmetric(horizontal: 16),
            sliver: SliverGrid(
              gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                childAspectRatio: 0.75,
                crossAxisSpacing: 16,
                mainAxisSpacing: 16,
              ),
              delegate: SliverChildBuilderDelegate(
                (context, index) {
                  final product = products[index];
                  return Card(
                    elevation: 2,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    clipBehavior: Clip.antiAlias,
                    child: InkWell(
                      onTap: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (_) => ProductDetailScreen(product: product)),
                        );
                      },
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Expanded(
                            child: Image.network(
                              product.imageUrl,
                              width: double.infinity,
                              fit: BoxFit.cover,
                              errorBuilder: (context, error, stackTrace) => Container(
                                color: Colors.grey[200],
                                child: Icon(Icons.broken_image, color: Colors.grey),
                              ),
                            ),
                          ),
                          Padding(
                            padding: const EdgeInsets.all(12.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  product.name,
                                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                ),
                                SizedBox(height: 4),
                                Text(
                                  'Từ ${product.minPrice.toStringAsFixed(0)}đ',
                                  style: TextStyle(color: primaryColor, fontWeight: FontWeight.w600),
                                ),
                                SizedBox(height: 4),
                                Text(
                                  product.stock > 0 ? 'Còn hàng: ${product.stock}' : 'Hết hàng',
                                  style: TextStyle(
                                    color: product.stock > 0 ? Colors.green : Colors.red,
                                    fontSize: 12,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ],
                            ),
                          )
                        ],
                      ),
                    ),
                  );
                },
                childCount: products.length,
              ),
            ),
          ),
          SliverToBoxAdapter(child: SizedBox(height: 30))
        ],
      ),
    );
  }
}

// ---------------- TAB 2: THỰC ĐƠN (MENU SẢN PHẨM) ----------------
class _MenuTab extends StatefulWidget {
  @override
  __MenuTabState createState() => __MenuTabState();
}

class __MenuTabState extends State<_MenuTab> {
  List<Category> _categories = [];
  List<Product> _products = [];
  int _selectedCategoryId = 0; 
  bool _isLoading = true;
  final TextEditingController _searchController = TextEditingController();

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  Future<void> _fetchData() async {
    setState(() => _isLoading = true);
    try {
      final catRes = await ApiService.get('/categories');
      if (catRes.statusCode == 200) {
        final catData = json.decode(catRes.body);
        _categories = (catData['data'] as List).map((e) => Category.fromJson(e)).toList();
        _categories.insert(0, Category(id: 0, name: 'Tất cả', slug: 'all'));
      }

      String prodEndpoint = '/products';
      List<String> queryParams = [];
      if (_selectedCategoryId != 0) {
        queryParams.add('category_id=$_selectedCategoryId');
      }
      if (_searchController.text.isNotEmpty) {
        queryParams.add('search=${_searchController.text}');
      }
      if (queryParams.isNotEmpty) {
        prodEndpoint += '?' + queryParams.join('&');
      }
      final prodRes = await ApiService.get(prodEndpoint);
      if (prodRes.statusCode == 200) {
        final prodData = json.decode(prodRes.body);
        _products = (prodData['data'] as List).map((e) => Product.fromJson(e)).toList();
      }
    } catch (e) {
      print('Error fetching data: $e');
    }
    setState(() => _isLoading = false);
  }

  void _onCategorySelected(int id) {
    if (_selectedCategoryId == id) return;
    setState(() => _selectedCategoryId = id);
    _fetchData();
  }

  @override
  Widget build(BuildContext context) {
    final primaryColor = Theme.of(context).primaryColor;

    return Scaffold(
      appBar: AppBar(
        title: Text('Thực đơn'),
        actions: [
          IconButton(
            icon: Icon(Icons.shopping_cart),
            onPressed: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => CartScreen()));
            },
          ),
        ],
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Padding(
                  padding: const EdgeInsets.all(12.0),
                  child: TextField(
                    controller: _searchController,
                    onSubmitted: (_) => _fetchData(),
                    decoration: InputDecoration(
                      hintText: 'Tìm kiếm sản phẩm...',
                      prefixIcon: Icon(Icons.search),
                      suffixIcon: IconButton(
                        icon: Icon(Icons.clear),
                        onPressed: () {
                          _searchController.clear();
                          _fetchData();
                        },
                      ),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(20),
                      ),
                      contentPadding: EdgeInsets.symmetric(vertical: 0),
                    ),
                  ),
                ),
                SizedBox(
                  height: 40,
                  child: ListView.builder(
                    scrollDirection: Axis.horizontal,
                    padding: EdgeInsets.symmetric(horizontal: 12),
                    itemCount: _categories.length,
                    itemBuilder: (context, index) {
                      final cat = _categories[index];
                      final isSelected = cat.id == _selectedCategoryId;
                      return GestureDetector(
                        onTap: () => _onCategorySelected(cat.id),
                        child: Container(
                          margin: EdgeInsets.symmetric(horizontal: 4),
                          padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                          decoration: BoxDecoration(
                            color: isSelected ? primaryColor : Colors.white,
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: primaryColor, width: 1),
                          ),
                          child: Center(
                            child: Text(
                              cat.name,
                              style: TextStyle(
                                color: isSelected ? Colors.white : primaryColor,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                        ),
                      );
                    },
                  ),
                ),
                SizedBox(height: 16),
                Expanded(
                  child: _products.isEmpty
                      ? Center(child: Text('Không có sản phẩm nào.'))
                      : GridView.builder(
                          padding: EdgeInsets.all(16),
                          gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 2,
                            childAspectRatio: 0.75,
                            crossAxisSpacing: 16,
                            mainAxisSpacing: 16,
                          ),
                          itemCount: _products.length,
                          itemBuilder: (context, index) {
                            final product = _products[index];
                                
                            return Card(
                              elevation: 2,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                              clipBehavior: Clip.antiAlias,
                              child: InkWell(
                                onTap: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(builder: (_) => ProductDetailScreen(product: product)),
                                  );
                                },
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Expanded(
                                      child: Image.network(
                                        product.imageUrl,
                                        width: double.infinity,
                                        fit: BoxFit.cover,
                                        errorBuilder: (context, error, stackTrace) => Container(
                                          color: Colors.grey[200],
                                          child: Icon(Icons.broken_image, color: Colors.grey),
                                        ),
                                      ),
                                    ),
                                    Padding(
                                      padding: const EdgeInsets.all(12.0),
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            product.name,
                                            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                                            maxLines: 1,
                                            overflow: TextOverflow.ellipsis,
                                          ),
                                          SizedBox(height: 4),
                                          Text(
                                            'Từ ${product.minPrice.toStringAsFixed(0)}đ',
                                            style: TextStyle(color: primaryColor, fontWeight: FontWeight.w600),
                                          ),
                                          SizedBox(height: 4),
                                          Text(
                                            product.stock > 0 ? 'Còn hàng: ${product.stock}' : 'Hết hàng',
                                            style: TextStyle(
                                              color: product.stock > 0 ? Colors.green : Colors.red,
                                              fontSize: 12,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        ],
                                      ),
                                    )
                                  ],
                                ),
                              ),
                            );
                          },
                        ),
                ),
              ],
            ),
    );
  }
}

// ---------------- TAB 3: TÀI KHOẢN ----------------
class _ProfileTab extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;

    return Scaffold(
      appBar: AppBar(title: Text('Tài khoản của tôi')),
      body: ListView(
        padding: EdgeInsets.all(16),
        children: [
          CircleAvatar(
            radius: 50,
            backgroundColor: Colors.grey[300],
            child: Icon(Icons.person, size: 50, color: Colors.grey[600]),
          ),
          SizedBox(height: 16),
          Text(user?.username ?? 'Khách', textAlign: TextAlign.center, style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
          Text(user?.email ?? '', textAlign: TextAlign.center, style: TextStyle(fontSize: 16, color: Colors.grey)),
          SizedBox(height: 32),
          ListTile(
            leading: Icon(Icons.history),
            title: Text('Lịch sử đơn hàng'),
            trailing: Icon(Icons.chevron_right),
            onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => OrderHistoryScreen()));
            },
          ),
          Divider(),
          ListTile(
            leading: Icon(Icons.logout, color: Colors.red),
            title: Text('Đăng xuất', style: TextStyle(color: Colors.red)),
            onTap: () async {
              await Provider.of<AuthProvider>(context, listen: false).logout();
              Navigator.of(context).pushReplacement(
                MaterialPageRoute(builder: (_) => LoginScreen()),
              );
            },
          ),
        ],
      ),
    );
  }
}
