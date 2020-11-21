@include('header_decentralization')

<div class="page-content">
    <div class="title-page"><a href="<?=url('/UserList');?>">USER</a> | USER CREATE</div>
    <form class="main-content">
        <div class="row-content">
            <label class="label-content">Select role (*)</label>
            <select id="role_id" class="select-content">
                <option disabled selected>Select role</option>
                <?php                    
                    foreach($role_list as $rl_item){
                        echo ('<option value="'.$rl_item->id.'">'.($rl_item->display_name).'</option>');
                    }  
                ?>
            </select>
        </div>     
        <div id="role_error" class="error-input-area"></div>
        <div class="row-content">
            <label class="label-content">User name (*)</label>
            <input id="user_name" class="input-content" placeholder="Input user name">
        </div>    
        <div id="user_name_error" class="error-input-area"></div>
        <div class="row-content">
            <label class="label-content">Email (*)</label>
            <input id="email" class="input-content" placeholder="Input email">
        </div>    
        <div id="email_error" class="error-input-area"></div>
        <div class="row-content">
            <label class="label-content">Password (*)</label>
            <input id="password" class="input-content" type="password" placeholder="Input password">
        </div>     
        <div id="password_error" class="error-input-area"></div>
        <div class="button-content-container">
            <button id="addNewUser" class="button-content" type="submit">Add new user</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {        
        $('#addNewUser').on('click', function(e){
            e.preventDefault();
            var formData = {
                "_token":       "{{ csrf_token() }}",
                'role_id':      $('#role_id').val(),
                'user_name':    $('#user_name').val(),    
                'email':        $('#email').val(),    
                'password':     $('#password').val(),
            };  

            $.ajax({                
                url: "<?=url('/AddNewUser');?>",
                type: "POST",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        $('#role_error, #user_name_error, #email_error, #password_error').text(''); 
                        $('form')[0].reset();

                        alert(data.success);
                        window.location.assign("<?=url('/UserList');?>");
                    }
                    else{
                        if (data.error.role_id){
                            $('#role_error').text("* " + data.error.role_id);
                        }

                        if (data.error.user_name){
                            $('#user_name_error').text("* " + data.error.user_name);
                        }

                        if (data.error.email){
                            $('#email_error').text("* " + data.error.email);
                        }

                        if (data.error.password){
                            $('#password_error').text("* " + data.error.password);
                        }
                    }
                },
                error: function(err){
                    console.log(err);
                }
            });
        }); 

        $('#role_id, #user_name, #password, #email').on('change', function(){ 
            var id = $(this).attr('id');

            switch(id){
                case "role_id":
                    $('#role_error').text(''); break;
                case "user_name":
                    $('#user_name_error').text(''); break;
                case "email":
                    $('#email_error').text(''); break;
                case "password":
                    $('#password_error').text(''); break;                
                default:
                    break;
            }            
        });

        /* Set active for menu */
        setActiveMenu('user_list');
    });
</script>

@include('footer_decentralization')
