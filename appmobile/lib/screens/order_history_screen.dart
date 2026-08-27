import 'dart:convert';
import 'package:flutter/material.dart';
import '../services/api_service.dart';

class OrderHistoryScreen extends StatefulWidget {
  @override
  _OrderHistoryScreenState createState() => _OrderHistoryScreenState();
}

class _OrderHistoryScreenState extends State<OrderHistoryScreen> {
  List<dynamic> _orders = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchOrders();
  }

  Future<void> _fetchOrders() async {
    setState(() => _isLoading = true);
    try {
      final res = await ApiService.get('/orders', auth: true);
      if (res.statusCode == 200) {
        final data = json.decode(res.body);
        setState(() {
          _orders = data['data'] ?? [];
        });
      }
    } catch (e) {
      print('Error fetching orders: $e');
    }
    setState(() => _isLoading = false);
  }

  String _formatStatus(String status) {
    switch (status.toUpperCase()) {
      case 'PENDING': return 'Đang xử lý';
      case 'CONFIRMED': return 'Đã xác nhận';
      case 'PREPARING': return 'Đang chuẩn bị';
      case 'DELIVERING': return 'Đang giao hàng';
      case 'COMPLETED': return 'Hoàn thành';
      case 'CANCELLED': return 'Đã hủy';
      default: return status;
    }
  }

  Color _getStatusColor(String status) {
    switch (status.toUpperCase()) {
      case 'PENDING': return Colors.orange;
      case 'CONFIRMED': return Colors.blue;
      case 'PREPARING': return Colors.cyan;
      case 'DELIVERING': return Colors.purple;
      case 'COMPLETED': return Colors.green;
      case 'CANCELLED': return Colors.red;
      default: return Colors.grey;
    }
  }

  void _showReviewDialog(Map<String, dynamic> item, int orderId) {
    int _rating = 5;
    TextEditingController _commentController = TextEditingController();

    showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setState) {
            return AlertDialog(
              title: Text('Đánh giá sản phẩm', style: TextStyle(fontSize: 18)),
              content: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text('Sản phẩm: ${item['product'] != null ? item['product']['name'] : 'N/A'}', style: TextStyle(fontWeight: FontWeight.bold)),
                    SizedBox(height: 16),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: List.generate(5, (index) {
                        return IconButton(
                          icon: Icon(
                            index < _rating ? Icons.star : Icons.star_border,
                            color: Colors.amber,
                            size: 32,
                          ),
                          onPressed: () {
                            setState(() {
                              _rating = index + 1;
                            });
                          },
                        );
                      }),
                    ),
                    SizedBox(height: 16),
                    TextField(
                      controller: _commentController,
                      decoration: InputDecoration(
                        hintText: 'Nhập nhận xét của bạn (tuỳ chọn)',
                        border: OutlineInputBorder(),
                      ),
                      maxLines: 3,
                    ),
                  ],
                ),
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.of(context).pop(),
                  child: Text('Hủy', style: TextStyle(color: Colors.grey)),
                ),
                ElevatedButton(
                  onPressed: () async {
                    Navigator.of(context).pop();
                    _submitReview(orderId, item['product_id'], _rating, _commentController.text);
                  },
                  child: Text('Gửi đánh giá'),
                ),
              ],
            );
          }
        );
      }
    );
  }

  Future<void> _submitReview(int orderId, int productId, int rating, String comment) async {
    try {
      final res = await ApiService.post('/reviews', {
        'order_id': orderId,
        'product_id': productId,
        'rating': rating,
        'comment': comment,
      }, auth: true);

      final data = json.decode(res.body);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(data['message'] ?? (data['success'] ? 'Cảm ơn bạn đã đánh giá!' : 'Có lỗi xảy ra.'))),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Lỗi kết nối khi gửi đánh giá.')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Lịch sử đơn hàng'),
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _orders.isEmpty
              ? Center(child: Text('Bạn chưa có đơn hàng nào.'))
              : ListView.builder(
                  padding: EdgeInsets.all(16),
                  itemCount: _orders.length,
                  itemBuilder: (context, index) {
                    final order = _orders[index];
                    final items = order['items'] as List;
                    
                    return Card(
                      margin: EdgeInsets.only(bottom: 16),
                      elevation: 2,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      child: Padding(
                        padding: EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(
                                  'Mã đơn: ${order['order_code']}',
                                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                                ),
                                Container(
                                  padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: _getStatusColor(order['order_status']).withOpacity(0.1),
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: Text(
                                    _formatStatus(order['order_status']),
                                    style: TextStyle(
                                      color: _getStatusColor(order['order_status']),
                                      fontWeight: FontWeight.bold,
                                      fontSize: 12,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            SizedBox(height: 12),
                            Text(
                              'Thời gian: ${order['created_at'] != null ? order['created_at'].toString().substring(0, 10) : ''}',
                              style: TextStyle(color: Colors.grey[600], fontSize: 13),
                            ),
                            Divider(height: 24),
                            ...items.map((item) {
                              return Padding(
                                padding: EdgeInsets.only(bottom: 8),
                                child: Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Expanded(
                                      child: Text(
                                        '${item['quantity']}x ${item['product'] != null ? item['product']['name'] : 'Sản phẩm'}',
                                        style: TextStyle(fontSize: 14),
                                      ),
                                    ),
                                    Text(
                                      '${double.tryParse((item['total_price'] ?? 0).toString())?.toStringAsFixed(0) ?? '0'}đ',
                                      style: TextStyle(fontSize: 14),
                                    ),
                                    if (order['order_status']?.toUpperCase() == 'COMPLETED') ...[
                                      SizedBox(width: 8),
                                      InkWell(
                                        onTap: () => _showReviewDialog(item, order['id']),
                                        child: Text('Đánh giá', style: TextStyle(color: Theme.of(context).primaryColor, fontSize: 13, decoration: TextDecoration.underline)),
                                      )
                                    ]
                                  ],
                                ),
                              );
                            }).toList(),
                            Divider(height: 24),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text('Tổng cộng', style: TextStyle(fontWeight: FontWeight.bold)),
                                Text(
                                  '${double.tryParse((order['total_amount'] ?? 0).toString())?.toStringAsFixed(0) ?? '0'}đ',
                                  style: TextStyle(
                                    fontWeight: FontWeight.bold, 
                                    color: Theme.of(context).primaryColor,
                                    fontSize: 16,
                                  ),
                                ),
                              ],
                            )
                          ],
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}
