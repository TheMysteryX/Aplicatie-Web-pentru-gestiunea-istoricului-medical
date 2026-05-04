@extends('layouts.app')
    
@section('content')
    <div class="top_index">
        <div class = "text">
            <h1> Bun venit! </h1>
            <p>Aici va puteti autentifica in contul dumneavoastra.</p>
            <p>Daca nu aveti un cont, puteti creea unul nou.</p>
        </div>
        <div class = "image">
            <img src = "img/doctor.png" width = "220px">
        </div>
    </div>
    
<div class = "middle">
    <div class="formular">
        <h2>Autentificare</h2>
    
            <form  id="formular-login" action="" method="POST">
                @csrf
                <p>
                    <label for="nume">Nume: </label>
                    <input type ="text" class="form-input" name="name" id="name">
                </p>
                <p>
                    <label for="email">Email:</label>
                    <input type="email" class="form-input" id="email" name="email">
                </p>
                <p>
                    <label for="parola">Parola:</label>
                    <input type ="password" class="form-input" name="password" id ="password">
                </p>
                <p>
                    <input type="submit" class="form-button" value="Log in">
                </p>
                <p>Nu ai cont? Creează unul nou.</p>
                <p>
                    <button type="button" class="form-button" onclick="location.href=''">Sign up</button>
                </p>
            </form>
    </div>
</div>
@endsection