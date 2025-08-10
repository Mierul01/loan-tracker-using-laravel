<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Payment Tracker</title>
    <link rel="icon" type="image/png" href="{{ asset('images/loan.png') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Loan Payment Tracker</h2>

        <br>
        <br>

       <!-- Payment Form with labels on the left -->
       <div class="d-flex justify-content-center my-4">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 20px;">
                <!-- Submit Form -->
                <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px; align-items: center;">
                    @csrf

                    <div style="display: flex; flex-direction: row; align-items: center; gap: 5px;">
                        <label for="amount">Enter Payment Amount (RM):</label>
                        <input type="text" name="amount" id="amount" placeholder="Enter amount" required />
                    </div>

                    <div style="display: flex; flex-direction: row; align-items: center; gap: 5px;">
                        <label for="document">Upload Document:</label>
                        <input type="file" name="document" id="document" accept="application/pdf" />
                    </div>

                    <button type="submit" class="submit-btn" style="background-color: darkred; color: white;">Submit</button>
                </form>

                <!-- Reset Form -->
                <form action="{{ route('payment.reset') }}" method="POST" onsubmit="return confirm('Clear ALL payment data from the database?')">
                    @csrf
                    <button type="submit" class="reset-btn" style="background-color: grey; color: white;">Reset</button>
                </form>
            </div>
        </div>

        <br>
        
        <!-- Payment Table -->
        <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
                <tr>
                    <th>Date</th>
                    <th>Amount (RM)</th>
                    <th>Balance (RM)</th>
                    <th>Document</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
              @foreach($payments as $payment)
              <tr>
                  <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                  <td>RM {{ number_format($payment->amount, 2) }}</td>
                  <td>RM {{ number_format($payment->balance, 2) }}</td>
                  <td>
                      @if($payment->document)
                          <a href="{{ asset('storage/' . $payment->document) }}" target="_blank">View</a>
                      @else
                          -
                      @endif
                  </td>
                  <td>
                      <form action="{{ route('payment.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Delete this payment?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" style="color: red; background: none; border: none;" title="Delete">
                              🗑️
                          </button>
                      </form>
                  </td>
              </tr>
              @endforeach
          </tbody>

            <tfoot>
                <tr>
                    <td colspan="2"><strong>Total Loan:</strong></td>
                    <td colspan="3">RM {{ number_format($totalLoan, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Current Balance:</strong></td>
                    <td colspan="3">RM {{ number_format($currentBalance, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Laravel Pagination Links -->
        <div class="pagination-centered-buttons">
            {{ $payments->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
