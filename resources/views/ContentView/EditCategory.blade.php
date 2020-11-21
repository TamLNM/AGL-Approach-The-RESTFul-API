@include('header_decentralization')

<div class="page-content">
    <div class="title-page"><a href="<?=url('/CategoryList');?>">CATEGORY</a> | EDIT CATEGORY INFORMATION</div>

    <form class="main-content">
        @csrf
        <div id="message_successfully" class="success-input-area"></div>  

        <div class="row-content">
            <label class="label-content">Title (*)</label><br>
            <input id="id" name="id" value="$category_list[0]->id;?>" hidden>
            <input id="title" name="title" category_id="<?=$category_list[0]->id;?>" value="<?=$category_list[0]->title;?>" class="input-content input-content-category" placeholder="Input title" style="width: 100%">
        </div>    
        <div id="title_error" class="error-input-area"></div>

        <div class="row-content">
            <label class="label-content">Description</label><br>
            <textarea id="description" category_id="<?=$category_list[0]->id;?>" value="<?=$category_list[0]->description;?>" class="input-content input-content-category" placeholder="Input description" style="width: 100%; min-width: 65%; min-height: 60px;"><?=$category_list[0]->description;?></textarea>
        </div>    
        <div class="error-input-area"></div>
     
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
            
            if ($('#title').val() == "<?=$category_list[0]->title;?>" &&
                $('#description').val() == "<?=$category_list[0]->description;?>"
            ){
                $('#message_successfully').text("DATA DOES NOT CHANGE!!!");
                return;
            }

            var formData = {
                "_token":                   "{{ csrf_token() }}",
                'id':                       $('#title').attr('category_id'),   
                'title':                    $('#title').val(),
                'description':              $('#description').val(),    
            };

            $.ajax({                
                url: "<?=url('/EditCategory');?>",
                type: "POST",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        $('#message_successfully').text(data.success);
                        setInterval(function(){ window.location.assign("<?=url('/CategoryList');?>"); }, 1500);              
                    }
                    else{
                        showMessage(data.error.title, 'title_error', '* ' + data.error.title);
                    }
                },
                error: function(err){
                    console.log(err);
                }
            });
        });

        $(document).on('click', '#btnNew', function(e){
            e.preventDefault();
            window.location.href = "AddCategory"
        });

        $('#btnDelete').on('click', function(e){
            e.preventDefault();
            var formData = {
                "_token":                   "{{ csrf_token() }}",
                'id':                       $('#title').attr('category_id'),
            };

            $.ajax({                
                url: "<?=url('/DeleteCategory');?>",
                type: "POST",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        alert(data.success)         
                        window.location.href = "<?=url('CategoryList');?>";
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
        
        /* Set active for menu */
        setActiveMenu('category_list');
    });
</script>

@include('footer_decentralization')