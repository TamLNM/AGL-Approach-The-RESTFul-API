@include('header_decentralization')

<div class="page-content">
    <div class="title-page"><a href="<?=url('/UserList');?>">USERS</a> | EDIT USER INFORMATION</div>

    <form class="main-content">
        <div id="message_successfully" class="success-input-area"></div>
        <div class="row-content">
            <label class="label-content">Select role (*)</label>
            <select id="role_id" class="select-content">
                <option disabled selected>Select role</option>
                <?php                    
                    foreach($role_list as $rl_item){
                        if ($rl_item->id == $user_info[0]->role_id){
                            echo ('<option selected value="'.$rl_item->id.'">'.($rl_item->display_name).'</option>');
                        }
                        else{
                            echo ('<option value="'.$rl_item->id.'">'.($rl_item->display_name).'</option>');
                        }
                    }  
                ?>
            </select>
        </div>     
        <div id="role_error" class="error-input-area" style="height: 40px;"></div>
        <div class="row-content">
            <label class="label-content">User name (*)</label>
            <input id="user_name" user_id="<?=$user_info[0]->id;?>" value="<?=$user_info[0]->user_name;?>" class="input-content" placeholder="Input user name">
        </div>    
        <div id="user_name_error" class="error-input-area" style="height: 40px;"></div>
        <div class="row-content">
            <label class="label-content">Email (*)</label>
            <input id="email" user_id="<?=$user_info[0]->id;?>" value="<?=$user_info[0]->email;?>" class="input-content" placeholder="Input email">
        </div>    
        <div id="email_error" class="error-input-area" style="height: 40px;"></div>
        <div class="row-content">
            <label class="label-content">New password</label>
            <input id="password" name="password" class="input-content" type="password" placeholder="Input new password">
        </div>     
        <div id="password_error" class="error-input-area" style="height: 40px;"></div>
        <div class="row-content">
            <label class="label-content">New password confirm</label>
            <input id="password_confirm" name="password_confirmation" class="input-content" type="password" placeholder="Input new password confirm">
        </div>     
        <div id="password_confirm_error" class="error-input-area" style="height: 40px;"></div>
        <div class="button-content-container button-edit-group">
            <button id="btnSave" class="button-content button-save" type="button">Save</button>
            <button id="btnNew" class="button-content button-new" type="button">New</button>
            <button id="btnDelete" class="button-content button-delete" type="button">Delete</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {        
        $('#btnSave').on('click', function(e){
            e.preventDefault();
            
            if (!($('#password').val()) && 
                !($('#password_confirm').val()) && 
                $('#role_id').val() == "<?=$user_info[0]->role_id;?>" &&
                $('#user_name').val() == "<?=$user_info[0]->user_name;?>" &&
                $('#email').val() == "<?=$user_info[0]->email;?>"
            ){
                $('#message_successfully').text("DATA DOES NOT CHANGE!!!");
                return;
            }


            var formData = {
                "_token":                   "{{ csrf_token() }}",
                'id':                       $('#user_name').attr('user_id'),   
                'role_id':                  $('#role_id').val(),
                'user_name':                $('#user_name').val(),    
                'email':                    $('#email').val(),    
                'password':                 $('#password').val(),
                'password_confirmation':    $('#password_confirm').val()
            };
            $.ajax({                
                url: "<?=url('/EditUser');?>",
                type: "POST",
                data: formData,
                success: function(data) {   
                    if ($.isEmptyObject(data.error)){
                        $('#message_successfully').text(data.success);
                        setInterval(function(){ window.location.href = "<?=url('/UserList');?>"; }, 1500);              
                    }
                    else{
                        if (data.error.password){
                            var passWordMsg = data.error.password[0];
                            if (data.error.password.length > 1){
                                for(i = 1; i < data.error.password.length; i++){
                                    passWordMsg += data.error.password[0];
                                }
                            }
                            showMessage(data.error.password, 'password_error', '* ' + passWordMsg);
                            showMessage(data.error.password_confirmation, 'password_confirm_error', '* ' + data.error.password_confirmation);
                        }

                        showMessage(data.error.role_error, 'role_error', '* ' + data.error.role);
                        showMessage(data.error.user_name, 'user_name_error', '* ' + data.error.user_name);
                        showMessage(data.error.email, 'email_error', '* ' + data.error.email);          

                    }
                },
                error: function(err){
                    console.log(err);
                }
            });
        });

        $('#role_id, #user_name, #password, #email, #password_confirm').on('change', function(){ 
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
                case "password_confirm":
                    $('#password_confirm_error').text(''); break;                   
                default:
                    break;
            }            
        }); 

        /* Set active for menu */
        setActiveMenu('user_list');

        /* Button New */
        $(document).on('click', '#btnNew', function(e){
            e.preventDefault();
            window.location.href = "<?=url('/AddNewUser');?>";
        });

        $(document).on('click', '#btnDelete', function(e){
            e.preventDefault();
            
            var formData = {
                "_token":                   "{{ csrf_token() }}",
                'id':                       $('#user_name').attr('user_id'),   
            };

            $.ajax({                
                url: "<?=url('/DeleteUser');?>",
                type: "POST",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        alert(data.success);        
                        window.location.href = "<?=url('UserList');?>";
                    }
                    else{
                        showMessage(data.error, 'message_successfully', '* ' + data.error);
                    }
                },
                error: function(err){
                    console.log(err);
                }
            });
        });
    });
</script>

@include('footer_decentralization')