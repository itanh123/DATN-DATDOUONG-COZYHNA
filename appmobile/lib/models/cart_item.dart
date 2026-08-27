import 'product.dart';
import 'topping.dart';

class CartItemTopping {
  final Topping topping;
  int quantity;

  CartItemTopping({required this.topping, this.quantity = 1});
  
  Map<String, dynamic> toJson() => {
    'topping_id': topping.id,
    'quantity': quantity,
    'unit_price': topping.price,
  };
}

class CartItem {
  final Product product;
  final ProductSize selectedSize;
  final List<CartItemTopping> toppings;
  int quantity;

  CartItem({
    required this.product,
    required this.selectedSize,
    required this.toppings,
    this.quantity = 1,
  });

  double get lineTotal {
    double topTotal = 0.0;
    for (var t in toppings) {
      topTotal += t.topping.price * t.quantity;
    }
    return (selectedSize.sellingPrice + topTotal) * quantity;
  }
}
