@include('header')

<div class="login-form">
    <form action="<?=url('/Login');?>" method="POST">
        <div class="container">
            @csrf
            <label class="login-title">LOGIN FORM</label>

            <div style="height: 25px"><?=$error?></div>

            <input class="inp-user-name" type="text" placeholder="User name" name="user_name" required>

            <input class="inp-password" type="password" placeholder="Password" name="password" required>

            <div style="height: 25px"></div>
            
            <div class="login-div">
                <label class="label-remember">
                    <input type="checkbox" checked="checked" name="remember"> Remember me
                </label>
                <button class="btn-login" type="submit">Login</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $('a').removeClass('active');
    $('.button-login').css({'color': '#333', 'background-color': 'white', 'font-weight': 'bold'});
</script>
@include('footer')