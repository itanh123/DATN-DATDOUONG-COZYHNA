class User {
  final int id;
  final String? name;
  final String username;
  final String email;
  final String phone;
  final String? avatar;

  User({
    required this.id,
    this.name,
    required this.username,
    required this.email,
    required this.phone,
    this.avatar,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      username: json['username'],
      email: json['email'],
      phone: json['phone'] ?? '',
      avatar: json['avatar'],
    );
  }
}
