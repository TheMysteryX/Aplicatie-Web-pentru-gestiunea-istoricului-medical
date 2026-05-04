@extends('layouts.app')
    
@section('content')
    <div class="top_index">
        <div class = "text">
            <h1> Bun venit! </h1><p></p>
            <p>Aici va puteti autentifica in contul dumneavoastra.</p>
            <p>Daca nu aveti un cont, puteti creea unul nou.</p>
        </div>
        <div class = "image">
            <img src = "../img/spital.png" width = "220px">
        </div>
    </div>
    
<div class = "middle">
    @if ($errors->any())
        <div class="card" style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif     
    @if (session('success'))
        <div class = "card" style="color:green">
            {{ session('success') }}
        </div>
    @endif  
    <div class="formular">
        <h2>Autentificare</h2>
    
            <form id="formular-login" action="" method="POST" onsubmit="return validForm();">
                @csrf
                <p></p>
                <label for="email">Email:</label><p></p>
                <input type="email" class="form-input" id="email" name="email"><p></p>
                <p></p>
                
                <label for="password">Parola:</label><p></p>
                <input type ="password" class="form-input" name="password" id ="password"><p></p>
                <p></p>
                
                <input type="submit" class="form-button" value="Log in"><p></p>
                <p></p><p></p>
                <p>Nu ai cont? Creează unul nou.</p>
                <p>
                    <button type="button" class="form-button" onclick="location.href='register'">Sign up</button>
                </p>
            </form>
    </div>
</div>

<script>
function validForm() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!email) { alert("Introduceti emailul!"); return false; }
    if (!password) {alert("Intoduceti parola!"); return false; }
    if (password.length < 6) { alert("Parola trebuie să aibă cel puțin 6 caractere!"); return false; }

    return true;
}
</script>
@endsection