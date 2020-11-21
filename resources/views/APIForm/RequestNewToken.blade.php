<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- CSS --> 
        <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
        <!-- Datatable CSS --> 
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        
        <style>
            body {
                font-family: 'Nunito';
                background-color: #0173A7;
            }
            h2{
                text-align: center;
                padding: 20px;
                color: #fff;
            }
            input{
                padding-left: 10px!important;
            }
            .main-content{
                border: none
            }
            form{
                background-color: #fff;
                padding: 20px!important;
                width: 40%!important;
            }
            #btnSendRequest{
                background-color: #0173A7!important;
                border: none;
            }
            .transferPage{
                padding: 10px;
                padding-top: 20px;
                width: 40%;
                margin-left: auto;
                margin-right: auto;
            }
            .transferPage a{
                color: #fff;
                padding: 5px;
                border-bottom: solid 1px #fff;
                font-weight: 100;
                text-decoration: none;
            }
            .transferPage a.active{
                font-weight: 1000;
                color: #FF8C00;
                border-color: #FF8C00;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="transferPage">
            <a href="<?=url('/api/requestNewToken')?>" class="active">Request new token</a>
            <a href="<?=url('/api/requestPostForm')?>">Request for publishing new blog</a>
        </div>
        <form id="form" class="main-content" enctype="multipart/form-data">
            @csrf
            <div class="row-content">
                <label class="label-content">User name *</label>
                <input id="username" name="username" class="input-content" placeholder="Input user name" style="width: 65%">
            </div>
            <div style="height: 15px;"></div>

            <div class="row-content">
                <label class="label-content">Password *</label>
                <input id="password" name="password" type="password" class="input-content" placeholder="Input password" style="width: 65%">
            </div>
            <div style="height: 15px;"></div>

            <div class="row-content">
                <label class="label-content">Your token</label>
                <input id="token" class="input-content" placeholder="" style="width: 65%; background-color: rgba(0,0,0,0.15); border: none" readonly>
            </div>
            <div style="height: 15px;"></div>

            <div class="button-content-container">
                <button id="btnSendRequest" class="button-content" type="submit">Send request</button>
            </div> 
        </form>
    </body>
    <script type="text/javascript">
        $(document).ready(function() {        
            $('#btnSendRequest').on('click', function(e){
                e.preventDefault();

                var formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');  
                formData.append('request_type', 'initialize');  
                formData.append('user_name', $('#username').val());  
                formData.append('password', $('#password').val());  

                $.ajax({                
                    url: "<?=url('/api/requestNewToken');?>",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if ($.isEmptyObject(data.error)){
                            $('#token').val(data.key);
                            alert('Request successfully! Your token is fill in corresponding input!');
                            console.log(data);
                        }
                        else{
                            alert(data.error);
                            console.log(data);
                        }
                    },
                    error: function(err){
                        console.log(err);
                    }
                });
                

            });
        });
    </script>
</html>
    
    
