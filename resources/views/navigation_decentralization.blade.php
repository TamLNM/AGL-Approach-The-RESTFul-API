<div class="header-for-admin" <?=$role[0]->name == "Admin" ? "" : "hidden";?> >
    <div class="navigation">
        <ul>
            <li><a class="page-icon-menu" id="icon" href="<?=url('/BlogList');?>"><img class="page-icon" src="{{ URL::asset('icon/logo.png') }}"></img></a></li>
            <li><a id="blog_list" href="<?=url('/BlogList');?>">Blog Management</a></li>
            <li><a id="category_list" href="<?=url('/CategoryList');?>">Category Management</a></li>
            <li><a id="user_list" href="<?=url('/UserList');?>">User Management</a></li>
        </ul>
    </div>
    <div class="logined-group">
        <p style="display: flex">Hello <span id="user-login" style="margin-left: 5px; margin-right: 5px;"><?=$role[0]->name?></span> (<span><a href="<?=url('/Logout');?>">Logout</a></span>)</p>
    </div>
</div>

<div class="header-for-editor" <?=$role[0]->name == "Admin" ? "hidden": "";?> >
    <div class="navigation">
        <ul>
            <li><a class="page-icon-menu" id="icon" href="<?=url('/BlogList');?>"><img class="page-icon" src="{{ URL::asset('icon/logo.png') }}"></img></a></li>
            <li><a id="blog_list1" href="<?=url('/BlogList');?>">Blog Management</a></li>
        </ul>
    </div>
    <div class="logined-group">
        <p style="display: flex">Hello <span id="user-login1" style="margin-left: 5px; margin-right: 5px;"><?=$role[0]->name?></span> (<span><a href="<?=url('/Logout');?>">Logout</a></span>)</p>
    </div>
</div>