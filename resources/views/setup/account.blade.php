<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Setup - Excavator</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-j8y0ITrvFafF4EkV1mPW0BKm6dp3c+J9Fky22Man50Ofxo2wNe5pT1oZejDH9/Dt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="app">

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header flex space-between items-center">
                        <p class="mb-0">Account Setup</p>
                    </div>

                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Joe Bloggs" required/>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="e.g. joe@bloggs.com" required/>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="e.g. (not) Password123!" required/>
                            </div>
                            <div class="form-group">
                                <label for="repeat_password">Repeat Password</label>
                                <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="e.g. (not) Password123!" required/>
                            </div>

                            @if ($error)
                                <div class="alert alert-danger">
                                    {{ $error }}
                                </div>
                            @endif

                            <button class="btn btn-success">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
