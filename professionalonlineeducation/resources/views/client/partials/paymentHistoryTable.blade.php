<table id="history" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($paymentLogs) && count($paymentLogs))
            @foreach ($paymentLogs as $paymentLog)
                <tr>
                    <td>{{ $paymentLog['log']->transaction_id }}</td>
                    <td>{{ $paymentLog['log']->created_at }}</td>
                    <td>
                        @if (!empty($paymentLog['courses']) && count($paymentLog['courses']))
                            @foreach ($paymentLog['courses'] as $course)
                                {{ $course->title }} <br />
                            @endforeach
                        @endif
                        @if (!empty($paymentLog['seminars']) && count($paymentLog['seminars']))
                            @foreach ($paymentLog['seminars'] as $seminar)
                                {{ $seminar->paymentHistoryDescription() }} <br />
                            @endforeach
                        @endif
                        @if ((empty($paymentLog['courses']) || !count($paymentLog['courses'])) && (empty($paymentLog['seminars']) || !count($paymentLog['seminars'])))
                            {{ $paymentLog['description'] ?? 'Course purchase' }} <br />
                        @endif
                    </td>
                    <td>${{ $paymentLog['log']->amount }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-muted">No payment history yet.</td>
            </tr>
        @endif
    </tbody>
</table>
