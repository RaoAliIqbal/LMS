<div class="row">
    <div class="col-md-6">
        <div id="source">
            <div class="k-widget" id="sourceFields_{{ $question->id }}">
                <h4>Correct Choice/Answer</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="target">
            <div class="k-widget" id="targetFields_{{ $question->id }}">
                <h4></h4>
            </div>
        </div>
        <textarea class="outputEle" id="output_{{ $question->id }}"></textarea>
    </div>
</div>




@push('scripts')


    <script>
        {{--questionId = {{ $question->id }};--}}
        /* Execute ---------- */
        $(function () {

            {{--$.each(sourceData, function(i, n) {--}}
            {{--    $("#sourceFields_{{ $question->id }}").append(n);--}}
            {{--    //$("#sourceFields_").append('<div class="sourceField k-widget">' + n + '</div>');--}}
            {{--});--}}

            var sourceData = [@foreach($setOne as $key=>$val) "{{ $val }}", @endforeach];
            var sourceDataZero = [@foreach($setZero as $key=>$val) "{{ $val }}", @endforeach];
            var targetData = {
                @foreach($setTwo as $key=>$val) field{{ $key }}: { title: "{{ $val }}", value: "" }, @endforeach
            };
           buildSourceList(sourceData,{{ $question->id }});
           buildTargetFields(targetData,{{ $question->id }},sourceDataZero);
           outputTargetData({{ $question->id }},targetData);
            //
            $(".sourceField_{{ $question->id }}").draggable({
                containment: "#form",
                cursor: "move",
                helper: "clone",
                revert: "invalid"
            });

            $(".targetField_{{ $question->id }}").droppable({
                accept: ".sourceField_{{ $question->id }}",
                hoverClass: "k-state-active",
                drop: function(event, ui) {
                    let questionKey = ui.draggable.attr('data-key');
                    setField(this, ui.draggable.text(),targetData,questionKey);
                    bindDelete(this,targetData);
                }
            });

            // $("#submit").click(function(event) {
            //     alert("Submitting:\n\n" + JSON.stringify(targetData));
            // });

        });
    </script>
@endpush
