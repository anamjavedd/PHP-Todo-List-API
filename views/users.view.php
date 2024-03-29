<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <?php require('partials/nav.php'); ?>
    <h1>All Users</h1>
    <!-- Users Table -->
    <?php foreach ($users as $user) : ?>
        <li><?= $user->name; ?></li>
    <?php endforeach; ?>
    
   <h1>Submit Your Name</h1>
   <form method="POST" action="/users">
        <input name="name"></input>
        <button type="submit">Submit</button>
   </form> 
  
</body>
</html>