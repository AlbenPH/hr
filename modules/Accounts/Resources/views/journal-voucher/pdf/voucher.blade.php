<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ asset('backend/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ module_asset('Accounts/css/acc-pdf.css?v_' . date('h_i')) }}" rel="stylesheet">
</head>

<body>
    <div class="tile">
        <div class="fs-10 text-start pb-3">
            {{ localize('print_date') }}: {{ \Carbon\Carbon::now()->format('d-m-Y h:i:sa') }} ,
            {{ localize('user') }}: {{ auth()->user()->full_name }}
        </div>
        <table style="width:100%">
            <tr>
                <td style="">
                    <img class="img-fluid" src="{{ app_setting()->logo }}">
                </td>
                <td>
                    <div class="text-center">
                        <div class="text-center fs-18">
                            {{ app_setting()->title }}
                        </div>
                        <strong><u class="pt-4 fs-14">{{ localize('journal_voucher') }}</u></strong>
                    </div>
                </td>
                <td>
                    <div class="text-end">
                        @php
                            $len = strlen(app_setting()->address);
                            $space = strrpos(app_setting()->address, ' ', -$len / 2);
                            $col1 = substr(app_setting()->address, 0, $space);
                            $col2 = substr(app_setting()->address, $space);
                        @endphp
                        <div class="fs-12">{{ $col1 }} <br> {{ $col2 }}</div>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="font-weight-700 mb-0 fs-12 pb-2">
                    <strong>{{ localize('voucher_no') }} :</strong> {{ $journal_voucher->voucher_no }}<br>
                    <strong>{{ localize('voucher_date') }} :</strong> {{ $journal_voucher->voucher_date }}
                </td>
            </tr>
        </table>
        <div class="table-responsive">
            <table id="" class="table display table-border table-hover fs-10 vertical-align">
                <thead>
                    <tr>
                        <th class="text-center">{{ localize('particulars') }}</th>
                        <th class="text-end" width="15%">@lang('language.debit') ({{ currency() }})</th>
                        <th class="text-end" width="15%">@lang('language.credit') ({{ currency() }})</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $debit = 0;
                        $credit = 0;
                    @endphp
                    @foreach (getVouchersByNo($journal_voucher->voucher_no) as $voucher)
                        <tr class="voucher-row">
                            <td>
                                <strong>
                                    {{ $voucher->acc_coa ? $voucher->acc_coa->account_name : ' ' }}
                                    @if ($voucher->subcode)
                                        -( {{ $voucher->subcode?->name }} )
                                    @endif
                                    @if ($voucher->ledger_comment)
                                        <br>
                                        <span> {{ $voucher->ledger_comment ?? ' ' }}</span>
                                    @endif
                                </strong>
                            </td>
                            <td class="text-end"> {{ bt_number_format($voucher->debit) }}</td>
                            <td class="text-end"> {{ bt_number_format($voucher->credit) }} </td>
                            @php
                                $debit += $voucher->debit;
                                $credit += $voucher->credit;
                                echo $credit;
                                echo $debit;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                            <strong>
                                {{ $voucher->acc_coa_reverse ? $voucher->acc_coa_reverse->account_name : ' ' }}
                            </strong>
                        </td>
                        @php
                            if ($debit > 0 && $credit == 0) {
                                $credit = $debit;
                                $debit = bt_number_format(0);
                            } else {
                                $debit = $credit;
                                $credit = bt_number_format(0);
                            }
                        @endphp
                        <td class="text-end"> {{ bt_number_format($debit) }}</td>
                        <td class="text-end"> {{ bt_number_format($credit) }} </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="voucher-footer">
                        <th class="text-end">{{ localize('total') }}</th>
                        <th class="text-end">
                            {{ bt_number_format(getVouchersByNo($journal_voucher->voucher_no)->sum('debit') == 0 ? getVouchersByNo($journal_voucher->voucher_no)->sum('credit') : getVouchersByNo($journal_voucher->voucher_no)->sum('debit')) }}
                        </th>
                        <th class="text-end">
                            {{ bt_number_format(getVouchersByNo($journal_voucher->voucher_no)->sum('credit') == 0 ? getVouchersByNo($journal_voucher->voucher_no)->sum('debit') : getVouchersByNo($journal_voucher->voucher_no)->sum('credit')) }}
                        </th>
                    </tr>
                    <tr>
                        <th class="" colspan="3">{{ localize('in_words') }} :
                            {{ ucwords(numberToWords(getVouchersByNo($journal_voucher->voucher_no)->sum('credit') == 0 ? getVouchersByNo($journal_voucher->voucher_no)->sum('debit') : getVouchersByNo($journal_voucher->voucher_no)->sum('credit'))) }}
                        </th>
                    </tr>
                    <tr>
                        <th class="" colspan="3">{{ localize('remarks') }} :
                            {{ $journal_voucher->narration ?? ' ' }}</th>
                    </tr>
                </tfoot>
            </table>
            <table class="w-100 mt-5">
                <tr class="mt-5">
                    <td class="w-25">
                        <div class="border-top me-2 text-center fs-12">{{ localize('received_by') }}</div>
                    </td>
                    <td class="w-25">
                        <div class="border-top ms-2 text-center fs-12">{{ localize('prepared_by') }}</div>
                    </td class="w-25">
                    <td>
                        <div class="border-top ms-2 text-center fs-12">{{ localize('executive_accounts') }}</div>
                    </td>
                    <td class="w-25">
                        <div class="border-top ms-2 text-center fs-12">{{ localize('approved_by') }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
