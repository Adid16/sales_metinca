<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Request Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Request Project</h1>
        <form action="{{ route('requests-project.update', $project->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $project->email }}" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <select name="subject" class="form-control" required>
                    <option value="quotation" {{ $project->subject == 'quotation' ? 'selected' : '' }}>Quotation</option>
                    <option value="other project" {{ $project->subject == 'other project' ? 'selected' : '' }}>Other Project</option>
                    <option value="meet and greet" {{ $project->subject == 'meet and greet' ? 'selected' : '' }}>Meet and Greet</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" class="form-control">{{ $project->message }}</textarea>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $project->phone }}">
            </div>
            <div class="form-group">
                <label for="company">Company</label>
                <input type="text" name="company" class="form-control" value="{{ $project->company }}">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('requests-project.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
