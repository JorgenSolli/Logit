<div id="routinePreview" class="modal modal-long fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fal fa-times"></i>
                </button>
                <h4 class="modal-title">Routine Preview</h4>
            </div>
            <div class="modal-body">
                <ul class="timeline timeline-simple">
                    @foreach ($routine as $exercise)
                        <li class="timeline-inverted">
                            <div class="timeline-badge @if ($exercise->is_warmup == 1) info @else success @endif">
                                <img src="/images/icons/muscle_groups/{{ $exercise->muscle_group }}.svg">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <span class="label @if ($exercise->is_warmup == 1) label-info @else label-success @endif">{{ $exercise->exercise_name }} @if ($exercise->is_warmup == 1) (warmup) @endif</span>
                                </div>
                                <div class="timeline-body">
                                    <p></p>
                                </div>
                                <h6>
                                    Rep Sets: {{ $exercise->goal_sets }}&nbsp;&nbsp;·&nbsp;&nbsp;
                                    Rep Goal: {{ $exercise->goal_reps }}&nbsp;&nbsp;·&nbsp;&nbsp;
                                    Weight Goal: {{ $exercise->goal_weight }}
                                </h6>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>