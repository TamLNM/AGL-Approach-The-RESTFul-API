@include('header')

<style>
    #example_filter.dataTables_filter{
        margin-bottom: 15px;
    }
    .button-group{
        display: flex;
        padding: 5px;
        padding-left: 20px;
    }
    .search-blog-input{
        width: 150px;
        border: solid 1px #333!important;
        padding: 5px
    }
    .search-blog-button{
        width: 75px;
        border: solid 1px #333!important;
        background-color: #333;
        color: white;
        padding: 5px
    }
</style>

<form method="POST" action="<?=url('/SearchBlog');?>">
    @csrf
    <div class="button-group">
        <input id="search-blog-input" type="text" class="search-blog-input" name="searchBlogInput" placeholder="">
        <button id="search-blog-button" class="search-blog-button" type="submit">Search</button>
    </div>
    <div class="blog-list">
        <table id="examples" class="table table-bordered table-striped display" width="100%">
            <thead hidden>
                <tr style="text-align: left;">
                    <th>Image</th>
                    <th>AllContent</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($blog_list as $item){ ?>
                <tr>
                    <td style="width: 15%; text-align: center">
                        <img src="{{ URL::asset($item->url) }}" style="max-width: 150px;">
                    </td>
                    <td>
                        <div>
                            <div style="display: flex; justify-content: space-between;">
                                <h2><?=$item->title;?></h2>
                                <!--<p style="padding-top: 5px;">10/16/2019 10:30:00 AM</p>-->
                                <p style="padding-top: 5px;"><?=$item->update_time;?></p>
                            </div>
                            <div style="font-size: 12px;"><?=$item->author;?></div>
                            <div><?=$item->content;?></div>
                            <div style="text-align: right; cursor: pointer; color: blue; text-decoration: underline;"><a href="<?=url('/BlogDetails?screen_name=blog_by_category&id='.$item->id);?>">More</a></div>
                        </div>
                    </td>              
                </tr>
                <?php } ?>
            </tbody>
            <tfoot hidden>
                <tr>
                    <th>Image</th>
                    <th>AllContent</th>
                </tr>
            </tfoot>
        </table>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() { 
        setActiveMenu('all_blog');
        
        // DataTable
            var table = $('#example').DataTable({
                initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        var that = this;
        
                        $( 'input', this.footer() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                that
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                    } );
                },
                bLengthChange: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: false,
                searchable: false,
            });

        /*
        var tbl = $('#examples').DataTable();        
        $(document).on('click', '#search-blog-button', function(){
            $.ajax({                
                url: "<?=url('/SearchBlog');?>",
                type: "POST",
                data: { "_token": "{{ csrf_token() }}", "search_content": $('#search-blog-input').val()},
                success: function(data) {
                    if ($.isEmptyObject(data.error)){
                        $('#examples tbody').html('');
                        for (i = 0; i < data.blog_list.length - 1; i++){
                            var src = "";

                            src = '<tr><td><img src="{{ URL::asset(' + data.blog_list[i].url + ') }}" style="max-width: 150px;"></td>';
                            src += '<td><div><div style="display: flex; justify-content: space-between;"><h2>' + data.blog_list[i]['title'] + '</h2><p style="padding-top: 5px;">' + data.blog_list[i]['update_time'] + '</p></div><div style="font-size: 12px;">' + data.blog_list[i]['author'] + '</div><div>' + data.blog_list[i]['content'] + '</div><div style="text-align: right; cursor: pointer; color: blue; text-decoration: underline;"><a href="' + <?="'".url('')."'";?> + '/BlogDetails?screen_name=blog_by_category&id=' + data.blog_list[i]['id'] + '">More</a></div></div></td></tr>';
                        
                            $('#examples tbody').append(src);
                        }       
                    }
                    else{
                        if (data.error.title_error){
                            alert("[ERROR]" + data.error.title);
                        }
                    }
                },
                error: function(err){
                    console.log(err);
                }
            });
        });
        */
    });
</script>

@include('footer')