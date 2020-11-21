@include('header_decentralization')

<div class="page-content">
    <div class="title-page"><a href="<?=url('/BlogList');?>">BLOG</a> | CREATE NEW BLOG </div>
    <form id="form" class="main-content" enctype="multipart/form-data">
        @csrf
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
            <select id="category_id" class="select-content" style="width: 65%">
                <option disabled selected>Select categories</option>
                <?php                    
                    foreach($category_list as $item){
                        echo ('<option value="'.$item->id.'">'.($item->title).'</option>');
                    }  
                ?>
            </select>
        </div>  
        <div id="category_error" class="error-input-area" style="height: 35px"></div>
        
        <div class="row-content">
            <label class="label-content">Content *</label><br>
            <textarea id="content" class="input-content input-content-category" placeholder="Input content" style="min-width: 65%; min-height: 60px;"></textarea>
        </div>    
        <div id="content_error" class="error-input-area" style="height: 35px"></div>
        
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

        <div class="button-content-container">
            <button id="addNewBlog" class="button-content" type="submit">Add new blog</button>
        </div> 
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {        
        $('#addNewBlog').on('click', function(e){
            e.preventDefault();
            // var formData = {
            //     "_token":       "{{ csrf_token() }}",
            //     'title':        $('#title').val(),
            //     'content':      $('#content').val(),
            //     'feature_images': $('#feature_images').val(),    
            //     'status':       $("input[name='status']:checked").val() == "public" ? 1 : 0,
            //     'user_id':      "",
            // };
            
            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');  
            formData.append('title', $('#title').val());  
            formData.append('content', $('#content').val());  
            formData.append('category_id', $('#category_id').val());  
            formData.append('feature_images', $('input[type=file]')[0].files[0]);  
            formData.append('status', $("input[name='status']:checked").val() == "public" ? 1 : 0);  
            
            $.ajax({                
                url: "<?=url('/AddBlog');?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        $('#title_error, #image_error, #category_error, #content-error').text(''); 
                        $('form')[0].reset();

                        alert(data.success);
                        window.location.assign("<?=url('/BlogList');?>");
                    }
                    else{
                        if (data.error.title){
                            $('#title_error').text("* " + data.error.title);
                        }

                        if (data.error.content){
                            $('#content_error').text("* " + data.error.content);
                        }

                        if (data.error.category){
                            $('#category_error').text("* " + data.error.category);
                        }

                        if (data.error.feature_images){
                            var msg = data.error.feature_images[0];
                            for (i = 1; i < data.error.feature_images.length; i++){
                                msg += (' ' + data.error.feature_images[i]);
                            }
                            $('#image_error').text("* " + msg);
                        }

                        console.log(data.error);
                    }
                },
                error: function(err){
                    console.log(err);
                }
            });
        });

        $('#title, #content, #category_id, #feature_images, #status').on('change', function(){ 
            var id = $(this).attr('id');

            switch(id){
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
                default:
                    break;
            }   
        }); 

        /* Set active for menu */
        setActiveMenu('blog_list');
    });
</script>
@include('footer_decentralization')
