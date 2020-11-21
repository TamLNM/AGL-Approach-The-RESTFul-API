@include('header_decentralization')

<div class="page-content">
    <div class="title-page"><a href="<?=url('/CategoryList');?>">CATEGORY</a> | CATEGORY CREATE</div>
    <form class="main-content">
        <div class="row-content">
            <label class="label-content">Title (*)</label>
            <input id="title" class="input-content" placeholder="Input title">
        </div>    
        <div id="title_error" class="error-input-area"></div>
        <div class="row-content">
            <label class="label-content">Description</label>
            <textarea id="description" class="input-content textarea-input" placeholder="Input description"></textarea>
        </div>
        <div id="" class="error-input-area"></div>
        <div class="button-content-container">
            <button id="addNewCategory" class="button-content" type="submit">Add new category</button>
        </div> 
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {        
        $('#addNewCategory').on('click', function(e){
            e.preventDefault();
            var formData = {
                "_token":       "{{ csrf_token() }}",
                'title':        $('#title').val(),
                'description':  $('#description').val(),    
            };  

            $.ajax({                
                url: "<?=url('/AddCategory');?>",
                type: "POST",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        $('#title_error').text(''); 
                        $('form')[0].reset();

                        alert(data.success);
                        setInterval(function(){ window.location.assign("<?=url('/CategoryList');?>"); }, 1500);              
                    }
                    else{
                        if (data.error.title_error){
                            $('#title_error').text("* " + data.error.title);
                        }
                    }
                },
                error: function(err){
                    console.log(err);
                }
            });
        }); 

        $('#title').on('change', function(){ 
            $('#title_error').text("");
        });
        
        /* Set active for menu */
        setActiveMenu('category_list');
    });
</script>

@include('footer_decentralization')