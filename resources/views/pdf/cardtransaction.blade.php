<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Transaction Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            height: 50px;
        }

        .user-info {
            text-align: right;
        }

        h2 {
            text-align: center;
            margin: 10px 0;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div style="display: inline-block; text-align: left;">
            <img src="{{ public_path('newUI/images/logo.png') }}" alt="logo" style="display: block; margin-bottom: 5px; width: 100px;">
            <div style="text-align: center; font-size: 12px; font-weight: bold;">
                {{ env('APP_NAME') }}
            </div>
            <div style="text-align: center; font-size: 7px; font-weight: bold; color: gray;">
                Leicester, United Kingdom
            </div>
        </div>
        <div class="user-info" style="width: 300px; margin-left: auto; font-size: 12px;">
            <table style="width: 100%; border: none; border-collapse: collapse;">
                <tr>
                    <td style="text-align: right; font-weight: bold; padding: 2px 8px; border: none;">For :</td>
                    <td style="text-align: left; padding: 2px 8px; border: none;">{{ $userName }}</td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold; padding: 2px 8px; border: none;">Downloaded on:</td>
                    <td style="text-align: left; padding: 2px 8px; border: none;">{{ now()->format('d M Y, H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <h2>Transaction Report</h2>
    <div class="subtitle">From {{ $start_date }} to {{ $end_date }}</div>

    <table>
        <thead>
            <tr>
                <th>Sender/Receiver</th>
                <th>Date</th>
                <th>Card Number</th>
                <th>Description</th>
                <th>Transaction Id</th>
                <th>Amount (USD)</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $cardTransaction)
            <tr>
                <td>
                    {{ $cardTransaction->cardholder_name }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($cardTransaction->transaction_date)->format('d M Y')}}
                </td>
                <td>
                    {{ substr($cardTransaction->card_number, -4) }}
                </td>
                <td>
                    {{ $cardTransaction->merchant_description }}
                </td>
                <td>
                    <small>
                        {{ $cardTransaction->transaction_id }}
                    </small>
                </td>
                <td>
                    <div>
                        @if ($cardTransaction->billing_amount >= 0)
                        <span style="color: green !important;">{{ $cardTransaction->billing_amount }}</span>
                        @else
                        <span style="color:red !impportant;">{{ $cardTransaction->billing_amount }}</span>
                        @endif
                    </div>
                </td>
                <td>
                    @if ($cardTransaction->is_credit == 'true')
                    <strong style="color: green !important;">Complete</strong>
                    @else
                    <strong style="color:red !impportant;">Pending</strong>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No transactions found .</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>