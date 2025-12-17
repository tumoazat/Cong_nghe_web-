<!DOCTYPE html>
<html>
<head>
    <title>Laravel App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .post {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .post h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Danh sách bài viết</h1>
    
    @foreach($posts as $post)
    <div class="post">
        <h2>{{ $post->title }}</h2>
        <p>{{ $post->content }}</p>
    </div>
    @endforeach
</body>
</html>