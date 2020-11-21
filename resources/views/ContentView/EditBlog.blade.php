@include('header_decentralization')
<?php /*dd(asset($blog_list[0]->url))*/ ?>
<div class="page-content">
    <div class="title-page"><a href="<?=url('/BlogList');?>">BLOG</a> | EDIT BLOG INFORMATION</div>
    <form class="main-content">
        <div class="row-content">
            <input hidden id="id" value="<?=$blog_list[0]->id;?>">
            <label class="label-content">Title *</label>
            <input id="title" class="input-content" placeholder="Input title" style="width: 65%" value="<?=$blog_list[0]->title;?>">
        </div>    
        <div id="title_error" class="error-input-area"></div>

        <div class="row-content">
            <!-- <img src="{{ URL::asset('images/1602495506.jpg') }}" style="max-width: 125px;"> -->
            <img src="{{ URL::asset($blog_list[0]->url) }}" style="max-width: 125px;">
        </div>
        <div class="row-content">
            <label class="label-content">Feature Images *</label>
            <input type="file" id="feature_images" name="feature_images" style="width: 65%;" value="<?=$blog_list[0]->image_name;?>">
        </div>    
        <div id="image_error" class="error-input-area"></div>

        <div class="row-content">
            <label class="label-content">Categories *</label>
            <select id="category_id" class="select-content" style="width: 65%">
                <option disabled selected>Select categories</option>
                <?php                    
                    foreach($category_list as $item){
                        if ($item->id == $blog_list[0]->category_id){
                            echo ('<option selected value="'.$item->id.'">'.($item->title).'</option>');
                        }
                        else{
                            echo ('<option value="'.$item->id.'">'.($item->title).'</option>');
                        }
                    }  
                ?>
            </select>
        </div>  
        <div id="category_error" class="error-input-area"></div>
        
        <div class="row-content">
            <label class="label-content">Content *</label><br>
            <textarea id="content" class="input-content input-content-category" placeholder="Input content" style="min-width: 65%; min-height: 60px;"><?=$blog_list[0]->content;?></textarea>
        </div>    
        <div id="content_error" class="error-input-area"></div>
        
        <div class="row-content">
            <label class="label-content">Status *</label>
            <div style="margin-top: 5px; text-align: left; width: 65%">
                <input type="radio" id="public" name="status" value="public" <?php if ($blog_list[0]->status == 1) echo ("checked"); else  echo (""); ?>>
                <label for="public" style="margin-right: 15px;">Public</label>
                <input type="radio" id="draft" name="status" value="draft" <?php if ($blog_list[0]->status == 1) echo (""); else  echo ("checked"); ?>>
                <label for="draft">Draft</label>
            </div>
        </div>    

        <div class="button-content-container button-edit-group">
            <button id="btnSave" class="button-content button-save">Save</button>
            <button id="btnNew" class="button-content button-new" type="button">New</button>
            <button id="btnDelete" class="button-content button-delete" type="button">Delete</button>
        </div> 
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() { 
        $('#btnSave').on('click', function(e){
            e.preventDefault();

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');  
            formData.append('id', $('#id').val());  
            formData.append('title', $('#title').val());  
            formData.append('content', $('#content').val());  
            formData.append('category_id', $('#category_id').val());  
            formData.append('feature_images', $('input[type=file]')[0].files[0]);  
            formData.append('status', $("input[name='status']:checked").val() == "public" ? 1 : 0);  
            
            $.ajax({                
                url: "<?=url('/EditBlog');?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        $('#title_error, #image_error, #category_error, #content-error').text(''); 
                        $('form')[0].reset();

                        alert(data.success);
                        window.location.assign("<?=url('/BlogList')?>");
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

        $(document).on('click', '#btnNew', function(e){
            e.preventDefault();
            window.location.href = "<?=url('AddBlog');?>";
        });

        $(document).on('click', '#btnDelete', function(e){
            e.preventDefault();
            var formData = {
                '_token':   '{{ csrf_token() }}',
                'id':       $('#id').val(),
            };

            $.ajax({                
                url: "<?=url('/DeleteBlog');?>",
                type: "POST",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        alert(data.success)         
                        window.location.href = "<?=url('BlogList');?>";
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