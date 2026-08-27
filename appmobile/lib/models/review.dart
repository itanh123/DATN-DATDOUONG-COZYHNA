import 'user.dart';

class Review {
  final int id;
  final int userId;
  final int productId;
  final int orderId;
  final int rating;
  final String? comment;
  final String status;
  final String? adminReply;
  final String createdAt;
  final User? user;

  Review({
    required this.id,
    required this.userId,
    required this.productId,
    required this.orderId,
    required this.rating,
    this.comment,
    required this.status,
    this.adminReply,
    required this.createdAt,
    this.user,
  });

  factory Review.fromJson(Map<String, dynamic> json) {
    return Review(
      id: json['id'],
      userId: json['user_id'],
      productId: json['product_id'],
      orderId: json['order_id'],
      rating: json['rating'],
      comment: json['comment'],
      status: json['status'] ?? 'pending',
      adminReply: json['admin_reply'],
      createdAt: json['created_at'],
      user: json['user'] != null ? User.fromJson(json['user']) : null,
    );
  }
}
