
<ul class="questionList">
    @if(count($chapter->topics))
        @foreach($chapter->topics as $topic)
            <li class="topic_{{ $topic->id }}">
                <div class="row">
                    <div class="form-group col-md-7">
                        <label for="">Title</label>
                        <p><b>{{ $topic->title }}</b></p>
                        <p><b>Exercise : {{ (isset($topic->exercise))?$topic->exercise->title:'None' }}</b></p>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-sm btn-warning" data-id="{{ $topic->id }}" data-exercise_id="{{ $topic->exercise_id }}" data-title="{{ $topic->title }}" data-description="{{ $topic->description }}" onclick="editTopic(this)">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger"  data-id="{{ $topic->id }}" onclick="removeLi(this)">Remove</button>
                    </div>
                </div>
            </li>
        @endforeach
    @endif
</ul>

<div class="form-row both">
    <div class="form-group col-sm-12">
        <button class="btn btn-sm btn-primary" type="button" id="AddTopic">Add Topic</button>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <div id="topicModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('saveTopic') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Topic</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                        <input type="hidden" name="topic_id" id="topicIc">
                        <input type="hidden" name="course_id" value="{{ $lessonId }}">
                        <div class="form-row both">
                            <div class="form-group col-sm-12">
                                <label>Title*</label>
                                {{ Helper::input('title','Title','',false,['id'=>'TopicTitle'],null) }}
                            </div>
                        </div>
                        <div class="form-row both">
                            <div class="form-group col-sm-6">
                                <label>Select Exercise</label>
                                <select  data-live-search="true" onchange="saveChapter()" id="exercise_id" name="exercise_id" class="form-control selectpicker">
                                    <option selected disabled>Select Exercise</option>
                                    @foreach($exercise as $item)
                                        <option  value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row both">
                            <div class="form-group col-sm-12">
                                <label>Description*</label>
                                <textarea id="TopicDescription" name="description" class="html">{{ isset($chapter->description)?$chapter->description:'' }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        let saveChap = '{{ route('admin_save_chap') }}'
        $("#AddTopic").click(function (){
            $("#TopicTitle").val('');
            $("#topicIc").val('');
            tinyMCE.get('TopicDescription').setContent('');
            $("#topicModal").modal('show');
        });
        function editTopic(t){
            const ele = $(t);
            let title = ele.data('title');
            let id = ele.data('id');
            let description = ele.data('description');
            let exercise_id = ele.data('exercise_id');
            $("#TopicTitle").val(title);
            $("#topicIc").val(id);
            $("#exercise_id").val(exercise_id);
            tinyMCE.get('TopicDescription').setContent(description);

            $("#topicModal").modal('show');
        }
        function removeLi(t){
            const ele = $(t);
            let id = ele.data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(".topic_"+id).remove();
                    $.ajax({
                        url: '{{ route("deleteTopic") }}',
                        method: 'POST',
                        data: {id: id},
                        success: function (res) {

                        }
                    });

                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })

        }
        function saveChapter(){
            const title = $(".ChapterTitle").val();
            const exercise_id = $("#exercise_id").val();
            const quiz_id = $("#quiz_id").val();
            const description = tinyMCE.get('description').getContent();//$("#description").val();
            const ChapterId = ' {{ $chapter->id }}';
            $.ajax({
                url: saveChap,
                method: 'POST',
                data: {title: title,ChapterId:ChapterId,exercise_id:exercise_id,quiz_id:quiz_id,description:description},
                success: function (res) {

                }
            });
        }
    </script>

    <style>
        ul.questionList {
            list-style: none;
            padding: 0;
            /* width: 50%; */
            margin-top: 21px;
            margin-bottom: 20px;
        }
        ul.questionList li {
            border: 1px solid #efefef;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            position: relative;
            width: 48%;
            display: inline-block;
        }
        ul.questionList li:hover {
            border-color: #323232;
        }
        ul.questionList li span {
            position: absolute;
            top: -11px;
            left: -5px;
            background: #fb0000;
            color: #fff;
            line-height: 1;
            padding: 3px 4px;
            border-radius: 50px;
            font-family: cursive;
            cursor: pointer;
        }
        ul.questionList button {
            margin-top: 40px;
        }
    </style>
@endpush
