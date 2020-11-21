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
        <h2 hidden>REQUEST FOR PUBLISHING NEW BLOG</h2>
        <div class="transferPage">
            <a href="<?=url('/api/requestNewToken')?>" >Request new token</a>
            <a href="<?=url('/api/requestPostForm')?>" class="active">Request for publishing new blog</a>
        </div>
        <form id="form" class="main-content" enctype="multipart/form-data">
            @csrf
            <div class="row-content">
                <label class="label-content">User name *</label>
                <input id="user_name" name="user_name" class="input-content" placeholder="Input user name" style="width: 65%">
            </div>
            <div style="height: 25px;"></div>

            <div class="row-content">
                <label class="label-content">Key *</label>
                <input id="key" name="key" class="input-content" placeholder="Input your token" style="width: 65%">
            </div>    
            <div id="token_error" class="error-input-area" style="height: 35px"></div>

            <div class="row-content">
                <label class="label-content">Title *</label>
                <input id="title" class="input-content" placeholder="Input title" style="width: 65%">
            </div>    
            <div id="title_error" class="error-input-area" style="height: 35px"></div>

            <div class="row-content">
                <label class="label-content">Feature Images *</label>
                <input type="file" id="feature_images" name="feature_images" style="width: 65%;">
            </div>    
            <div id="image_error" class="error-input-area" style="height: 35px"></div>

            <div class="row-content">
                <label class="label-content">Categories *</label>
                <input id="category_id" name="category_id" class="input-content" placeholder="Input category title" style="width: 65%">
            </div>  
            <div id="category_error" class="error-input-area" style="height: 35px"></div>
            
            <div class="row-content">
                <label class="label-content">Content *</label><br>
                <textarea id="content" class="input-content input-content-category" placeholder="Input content" style="min-width: 65%; min-height: 60px;"></textarea>
            </div>    
            <div id="content_error" class="error-input-area" style="height: 35px"></div>

            <div class="row-content">
                <label class="label-content">Description *</label><br>
                <textarea id="description" class="input-content input-content-category" placeholder="Input description" style="min-width: 65%; min-height: 60px;"></textarea>
            </div>    
            <div id="description_error" class="error-input-area" style="height: 35px"></div>
            
            <div class="row-content">
                <label class="label-content">Status *</label>
                <div style="margin-top: 5px; text-align: left; width: 65%">
                    <input type="radio" id="public" name="status" value="public" checked>
                    <label for="public" style="margin-right: 15px;">Public</label>
                    <input type="radio" id="draft" name="status" value="draft">
                    <label for="draft">Draft</label>
                </div>
            </div>    
            <div id="content-error" class="error-input-area"></div>

            <div style="height: 15px;"></div>
            <div class="row-content">
                <label class="label-content">Next request token</label>
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
                formData.append('request_type', 'requestPost');  
                formData.append('user_name',  $('#user_name').val());  
                formData.append('key', $('#key').val());  
                formData.append('title', $('#title').val());  
                formData.append('content', $('#content').val());  
                formData.append('category_id', $('#category_id').val());  
                formData.append('description', $('#description').val());  
                formData.append('feature_images', $('input[type=file]')[0].files[0]);  
                formData.append('status', $("input[name='status']:checked").val() == "public" ? 1 : 0);  

                // var formData = {
                //     '_token': '{{ csrf_token() }}',
                //     'request_type': 'requestPost',
                //     'user_name':  $('#user_name').val(),
                //     'key': $('#key').val(),
                //     'title': $('#title').val(),
                //     'content': $('#content').val(),
                //     'category_id': $('#category_id').val(),
                //     'feature_images': $('input[type=file]')[0].files[0],
                //     'status': $("input[name='status']:checked").val() == "public" ? 1 : 0,
                // }

                $.ajax({                
                    url: "<?=url('/api/requestPostForm');?>",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if ($.isEmptyObject(data.error) && $.isEmptyObject(data.key_error)){
                            $('#token_error, #title_error, #image_error, #category_error, #content-error').text(''); 

                            alert('Request successfully! Your token is fill in corresponding input!');
                            $('#token').val(data.key);
                        }
                        else{
                            $('#token_error, #title_error, #image_error, #category_error, #content-error').text(''); 
                            if (data.key_error){
                                $('#token_error').text("* " + data.key_error);
                            }        

                            if (data.error){
                                if (data.error.title){
                                    $('#title_error').text("* " + data.error.title);
                                }

                                if (data.error.content){
                                    $('#content_error').text("* " + data.error.content);
                                }

                                if (data.error.category){
                                    $('#category_error').text("* " + data.error.category);
                                }

                                if (data.error.description){
                                    $('#description_error').text("* " + data.error.category);
                                }
                                
                                if (data.error.feature_images){
                                    var msg = data.error.feature_images[0];
                                    for (i = 1; i < data.error.feature_images.length; i++){
                                        msg += (' ' + data.error.feature_images[i]);
                                    }
                                    $('#image_error').text("* " + msg);
                                }
                            }                            

                            console.log(data.error);
                        }
                    },
                    error: function(err){
                        console.log(err);
                    }
                });
                

            });

            $('#title, #content, #category_id, #feature_images, #status, #description').on('change', function(){ 
                var id = $(this).attr('id');

                switch(id){
                    case "key":
                        $('#key').text(''); break;
                    case "title":
                        $('#title_error').text(''); break;
                    case "content":
                        $('#content_error').text(''); break;
                    case "category_id":
                        $('#category_error').text(''); break;
                    case "feature_images":
                        $('#image_error').text(''); break;  
                    case "status":
                        $('#status_error').text(''); break;  
                    case "description":
                        $('#description_error').text(''); break;                
                    default:
                        break;
                }   
            }); 
        });
    </script>
</html>
    
    
