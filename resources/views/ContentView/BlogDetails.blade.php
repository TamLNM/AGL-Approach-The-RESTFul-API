@include('header')
<link rel="stylesheet" href="{{ URL::asset('css/blog-details.css') }}">

<div class="blog-list">
    <table id="example" class="display" style="width:100%">
        <thead hidden>
            <tr style="text-align: left;">
                <th>Blog Content Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="blog-details-title">
                        <h1><?=$blog_list[0]->title;?></h1>
                    </div>
                    <div class="blog-details-img-block">
                        <img class="blog-details-img" src="{{ URL::asset($blog_list[0]->url) }}">
                    </div>
                    <div class="blog-details-content">
                        <p><?=$blog_list[0]->content;?></p>
                    </div>
                    <div class="blog-details-author">
                        <p><?=$blog_list[0]->author;?></p>
                    </div>
                    <div class="blog-details-button-block">
                        <button id="btnPrevious" class="blog-details-button">< Previous</button>
                        <button id="btnNext" class="blog-details-button">Next ></button>
                    </div>
                </td>
            <tr>
        </tbody>
        <tfoot hidden>
            <tr>
                <th>Blog Content Details</th>
            </tr>
        </tfoot>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() { 
        setActiveMenu('<?=$screen_name?>');
        setDataTable('examples');

        var currentID = <?=$current_id;?>, max_id = <?=$max_id;?>, min_id = <?=$min_id;?>;
        checkButtonAndSetDisable(currentID, max_id, min_id);

        $('#btnPrevious, #btnNext').on('click', function(){
            var id = $(this).attr('id');
            if (id == 'btnPrevious'){
                /* Click Button Previous Event */              
                var url = <?="'".url('/BlogDetails?screen_name='.$screen_name.'&id=')."'";?> + (currentID - 1);
                location.href = url;
                return;
            }
            
            /* Click Button Next Event */
            var url = <?="'".url('/BlogDetails?screen_name='.$screen_name.'&id=')."'";?> + (parseInt(currentID) + 1);
            location.href = url;
        });

        function checkButtonAndSetDisable(currentID, max_id, min_id){
            $('#btnPrevious, #btnNext').prop('disabled', false);

            if (currentID - 1 < min_id){
                $('#btnPrevious').prop('disabled', true);
            }

            if (parseInt(currentID) + 1 > max_id){
                $('#btnNext').prop('disabled', true);
            }
        }
    });
</script>

@include('footer')