<div id="routinePreview" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Routine Preview</h4>
            </div>
            <div class="modal-body">
                <ul class="timeline timeline-simple">
                    @foreach ($routines as $routine)
                        <li class="timeline-inverted">
                            <div class="timeline-badge @if ($routine->is_warmup == 1) info @else success @endif">
                                <img src="/images/icons/muscle_groups/{{ $routine->muscle_group }}.svg">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <span class="label @if ($routine->is_warmup == 1) label-info @else label-success @endif">{{ $routine->exercise_name }} @if ($routine->is_warmup == 1) (warmup) @endif</span>
                                </div>
                                <div class="timeline-body">
                                    <p></p>
                                </div>
                                <h6>
                                    Rep Sets: {{ $routine->goal_sets }}&nbsp;&nbsp;·&nbsp;&nbsp;
                                    Rep Goal: {{ $routine->goal_reps }}&nbsp;&nbsp;·&nbsp;&nbsp;
                                    Weight Goal: {{ $routine->goal_weight }}
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