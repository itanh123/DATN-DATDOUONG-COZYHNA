import 'dart:convert';
import 'package:flutter/material.dart';
import '../services/api_service.dart';

class AiChatScreen extends StatefulWidget {
  const AiChatScreen({super.key});

  @override
  _AiChatScreenState createState() => _AiChatScreenState();
}

class _AiChatScreenState extends State<AiChatScreen> {
  final TextEditingController _textController = TextEditingController();
  final List<Map<String, dynamic>> _messages = [];
  bool _isLoading = false;
  String? _sessionId;
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _messages.add({
      'role': 'assistant',
      'text': 'Chào bạn! Mình là trợ lý AI của CozyHNA. Mình có thể giúp gì cho bạn hôm nay?',
    });
  }

  void _scrollToBottom() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (_scrollController.hasClients) {
        _scrollController.animateTo(
          _scrollController.position.maxScrollExtent,
          duration: Duration(milliseconds: 300),
          curve: Curves.easeOut,
        );
      }
    });
  }

  Future<void> _sendMessage(String text) async {
    if (text.trim().isEmpty) return;

    _textController.clear();
    setState(() {
      _messages.add({'role': 'user', 'text': text});
      _isLoading = true;
    });
    _scrollToBottom();

    try {
      final payload = {
        'message': text,
        'session_id': _sessionId,
      };

      final response = await ApiService.post('/ai/chat', payload, auth: true);
      
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        _sessionId = data['session_id']?.toString();
        
        setState(() {
          _messages.add({
            'role': 'assistant',
            'text': data['text'] ?? 'Lỗi không xác định',
            'type': data['type'],
            'data': data['data'],
          });
        });
      } else {
        setState(() {
          _messages.add({
            'role': 'assistant',
            'text': 'Xin lỗi, kết nối tới máy chủ AI đang gặp sự cố. Vui lòng thử lại sau.',
          });
        });
      }
    } catch (e) {
      setState(() {
        _messages.add({
          'role': 'assistant',
          'text': 'Lỗi kết nối mạng: $e',
        });
      });
    }

    setState(() {
      _isLoading = false;
    });
    _scrollToBottom();
  }

  Widget _buildMessage(Map<String, dynamic> msg) {
    final isUser = msg['role'] == 'user';
    return Container(
      margin: EdgeInsets.symmetric(vertical: 8, horizontal: 16),
      child: Row(
        mainAxisAlignment: isUser ? MainAxisAlignment.end : MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (!isUser) ...[
            CircleAvatar(
              backgroundColor: Theme.of(context).primaryColor,
              child: Icon(Icons.smart_toy, color: Colors.white, size: 20),
            ),
            SizedBox(width: 8),
          ],
          Flexible(
            child: Container(
              padding: EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: isUser ? Theme.of(context).primaryColor : Colors.grey[200],
                borderRadius: BorderRadius.circular(16).copyWith(
                  bottomRight: isUser ? Radius.zero : Radius.circular(16),
                  topLeft: !isUser ? Radius.zero : Radius.circular(16),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    msg['text'] ?? '',
                    style: TextStyle(
                      color: isUser ? Colors.white : Colors.black87,
                      fontSize: 15,
                    ),
                  ),
                  if (msg['type'] == 'order_status' && msg['data'] != null) ...[
                    SizedBox(height: 8),
                    Container(
                      padding: EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: isUser ? 0.2 : 0.6),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Mã đơn: ${msg['data']['order_code']}', style: TextStyle(fontWeight: FontWeight.bold)),
                          Text('Trạng thái: ${msg['data']['order_status']}'),
                          Text('Tổng tiền: ${msg['data']['total_amount']}đ'),
                        ],
                      ),
                    )
                  ]
                ],
              ),
            ),
          ),
          if (isUser) SizedBox(width: 48), // Padding equivalent to avatar
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Icon(Icons.smart_toy),
            SizedBox(width: 8),
            Text('Trợ lý AI CozyHNA'),
          ],
        ),
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              controller: _scrollController,
              itemCount: _messages.length,
              itemBuilder: (context, index) {
                return _buildMessage(_messages[index]);
              },
            ),
          ),
          if (_isLoading)
            Padding(
              padding: const EdgeInsets.all(8.0),
              child: Row(
                children: [
                  SizedBox(width: 48),
                  SizedBox(
                    width: 20, height: 20,
                    child: CircularProgressIndicator(strokeWidth: 2),
                  ),
                  SizedBox(width: 8),
                  Text('AI đang suy nghĩ...', style: TextStyle(color: Colors.grey)),
                ],
              ),
            ),
          Container(
            padding: EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: Colors.white,
              boxShadow: [BoxShadow(color: Colors.black12, offset: Offset(0, -1), blurRadius: 4)],
            ),
            child: SafeArea(
              child: Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: _textController,
                      decoration: InputDecoration(
                        hintText: 'Hỏi AI về đồ uống, đơn hàng...',
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(24),
                          borderSide: BorderSide.none,
                        ),
                        filled: true,
                        fillColor: Colors.grey[200],
                        contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      ),
                      onSubmitted: _sendMessage,
                    ),
                  ),
                  SizedBox(width: 8),
                  CircleAvatar(
                    backgroundColor: Theme.of(context).primaryColor,
                    child: IconButton(
                      icon: Icon(Icons.send, color: Colors.white),
                      onPressed: () => _sendMessage(_textController.text),
                    ),
                  )
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
