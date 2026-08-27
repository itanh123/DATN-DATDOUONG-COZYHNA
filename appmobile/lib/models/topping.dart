class Topping {
  final int id;
  final String name;
  final double price;
  final String? image;

  Topping({
    required this.id,
    required this.name,
    required this.price,
    this.image,
  });

  factory Topping.fromJson(Map<String, dynamic> json) {
    return Topping(
      id: json['id'],
      name: json['name'],
      price: double.tryParse(json['price'].toString()) ?? 0.0,
      image: json['image'],
    );
  }
}
