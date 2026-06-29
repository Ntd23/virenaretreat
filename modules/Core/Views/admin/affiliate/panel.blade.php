@if(is_default_lang())
    <div class="panel">
        <div class="panel-title"><strong>{{__("Affiliate Setting")}}</strong></div>
        <div class="panel-body">
            <div class="form-group">
                <label class="d-flex align-items-center">
                    <input type="checkbox" name="is_affiliate" @if(!empty($row->is_affiliate)) checked @endif value="1" class="mr-2"> {{__("Enable Affiliate")}}
                </label>
            </div>
            <div class="form-group" id="affiliate_config_group" style="display: {{ !empty($row->is_affiliate) ? 'block' : 'none' }}">
                <div class="mb-3">
                    <label class="control-label font-weight-bold">{{__("Commission Type")}}</label>
                    <select name="affiliate_commission_type" class="form-control custom-select">
                        <option value="percent" @if(($row->affiliate_commission_type ?? 'percent') == 'percent') selected @endif>{{__("Percentage (%)")}}</option>
                        <option value="fixed" @if(($row->affiliate_commission_type ?? '') == 'fixed') selected @endif>{{__("Fixed Amount")}}</option>
                    </select>
                </div>
                <div>
                    <label class="control-label font-weight-bold">{{__("Commission Value")}}</label>
                    <input type="number" step="0.01" min="0" name="affiliate_commission_value" class="form-control" value="{{ old('affiliate_commission_value', $row->affiliate_commission_value ?? 0) }}">
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('input[name="is_affiliate"]').change(function() {
                if($(this).is(':checked')) {
                    $('#affiliate_config_group').slideDown();
                } else {
                    $('#affiliate_config_group').slideUp();
                }
            });
        });
    </script>
@endif
