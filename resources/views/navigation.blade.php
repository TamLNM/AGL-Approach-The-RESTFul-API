<div class="header-for-everyone">
    <div class="navigation">
        <ul>
            <li><a class="page-icon-menu" id="icon" href="<?=url('/Home');?>"><img class="page-icon" src="{{ URL::asset('icon/logo.png') }}"></img></a></li>
            <li><a id="home" class="active" href="<?=url('/Home');?>">Home</a></li>
            <li><a id="all_blog" href="<?=url('/AllBlog');?>">All Blog</a></li>
            <li><a id="blog_by_category" href="<?=url('/BlogByCategory?category_id=All');?>">Category</a></li>
            <li><a id="contact" href="<?=url('/Contact');?>">Contact</a></li>
            <li><a id="about" href="<?=url('/About');?>">About</a></li>
        </ul>
    </div>
    <div class="login-group">
        <button id="btnLogin" class="button-login">Login</label>
        <button class="button-register">Register</label>
    </div>
</div>