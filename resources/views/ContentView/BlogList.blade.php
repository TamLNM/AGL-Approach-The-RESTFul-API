@include('header_decentralization')
<div class="blog-list">
    <button class="btn-add-new-blog"><a href="<?=url('/AddBlog');?>">Add new blog</a></button>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr style="text-align: left;">
                <th>Title</th>
                <th>Content</th>
                <th>Category</th>
                <th>Status</th>
                <th>Last update date</th>
                <th>Author</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($blog_list as $blog){
                echo "<tr>";
                    echo "<td blog_id='".($blog->id)."'>".($blog->title)."</td>";
                    echo "<td>".($blog->content)."</td>";
                    echo "<td>".($blog->category)."</td>";
                    echo "<td>"."</td>";
                    echo "<td>"."</td>";
                    echo "<td>"."</td>";
                echo "</tr>";
                
            }?>
        </tbody>
        <tfoot hidden>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Category</th>
                <th>Status</th>
                <th>Last update date</th>
                <th>Author</th>
            </tr>
        </tfoot>
    </table>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            } );
        
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

            /* Click on column Name */
            $(document).on("click", "#example tbody td:nth-child(1)", function(){
                var blog_id = $(this).attr('blog_id'),
                    url = <?="'".url('/EditBlog/?id=')."'";?> + blog_id;

                location.href = url;
            });

            /* Set active for menu */
            setActiveMenu('blog_list');
        });
    </script>
</div>

@include('footer_decentralization')