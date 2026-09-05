import 'dart:async';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:google_sign_in/google_sign_in.dart';
import '../providers/auth_provider.dart';
import '../widgets/web_signin_button.dart';
import 'home_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  StreamSubscription? _googleSignInSubscription;

  @override
  void initState() {
    super.initState();
    if (kIsWeb) {
      _initGoogleSignInWeb();
    }
  }

  void _initGoogleSignInWeb() async {
    try {
      await GoogleSignIn.instance.initialize();
    } catch (e) {
      // Đã khởi tạo trước đó
    }

    _googleSignInSubscription = GoogleSignIn.instance.authenticationEvents.listen((event) {
      if (event is GoogleSignInAuthenticationEventSignIn) {
         _handleGoogleSignIn(event.user);
      }
    }, onError: (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Lỗi Google: $error')),
      );
    });
  }

  @override
  void dispose() {
    _googleSignInSubscription?.cancel();
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _handleGoogleSignIn(GoogleSignInAccount googleUser) async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    bool success = await authProvider.googleLogin(
      googleUser.email,
      googleUser.id,
      googleUser.displayName ?? 'Người dùng Google',
    );

    if (success) {
      Navigator.of(context).pushReplacement(
        MaterialPageRoute(builder: (_) => HomeScreen()),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Đăng nhập Google thất bại trên Server.')),
      );
    }
  }

  void _submit() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    bool success = await authProvider.login(
      _emailController.text,
      _passwordController.text,
    );

    if (success) {
      Navigator.of(context).pushReplacement(
        MaterialPageRoute(builder: (_) => HomeScreen()),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Đăng nhập thất bại. Kiểm tra lại email/mật khẩu.')),
      );
    }
  }

  void _loginWithGoogle() async {
    try {
      final GoogleSignInAccount googleUser = await GoogleSignIn.instance.authenticate();
      if (googleUser != null) {
        _handleGoogleSignIn(googleUser);
      }
    } catch (e) {
      print("Lỗi Google Sign In: $e");
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Lỗi Google: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final isLoading = Provider.of<AuthProvider>(context).isLoading;
    final primaryColor = Theme.of(context).primaryColor;

    return Scaffold(
      body: Center(
        child: SingleChildScrollView(
          padding: EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Image.asset('assets/images/logo.png', width: 120, height: 120),
              SizedBox(height: 16),
              Text(
                'COZY HNA',
                style: TextStyle(
                  fontSize: 28,
                  fontWeight: FontWeight.bold,
                  color: primaryColor,
                ),
              ),
              SizedBox(height: 32),
              TextField(
                controller: _emailController,
                decoration: InputDecoration(
                  labelText: 'Email',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  prefixIcon: Icon(Icons.email),
                ),
                keyboardType: TextInputType.emailAddress,
              ),
              SizedBox(height: 16),
              TextField(
                controller: _passwordController,
                decoration: InputDecoration(
                  labelText: 'Mật khẩu',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  prefixIcon: Icon(Icons.lock),
                ),
                obscureText: true,
              ),
              SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: isLoading ? null : _submit,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: primaryColor,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: isLoading
                      ? CircularProgressIndicator(color: Colors.white)
                      : Text('Đăng nhập', style: TextStyle(fontSize: 18)),
                ),
              ),
              SizedBox(height: 16),
              if (kIsWeb)
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: buildWebSignInButton(),
                )
              else
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: OutlinedButton.icon(
                    onPressed: isLoading ? null : _loginWithGoogle,
                    icon: Image.asset('assets/images/google_logo.png', width: 24, height: 24, errorBuilder: (c,e,s) => Icon(Icons.g_mobiledata, color: Colors.red, size: 32)),
                    label: Text('Đăng nhập bằng Google', style: TextStyle(fontSize: 16, color: Colors.black87)),
                    style: OutlinedButton.styleFrom(
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                      side: BorderSide(color: Colors.grey.shade300),
                    ),
                  ),
                ),
              SizedBox(height: 16),
              TextButton(
                onPressed: () {
                  // Chuyển sang màn hình đăng ký (sẽ làm sau)
                },
                child: Text('Chưa có tài khoản? Đăng ký ngay'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
