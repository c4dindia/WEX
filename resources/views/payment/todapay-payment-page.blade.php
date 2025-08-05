<!doctype html>
<html lang="en">
  <head>
    <title>Todapay Payment Page</title></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://unpkg.com/@paycore/merchant-widget-js@0.4.1/dist/merchantWidget.umd.js"></script>
</head>
  <body>
    <div id="merchant-widget-mount-point"></div>
    <script>
    window.widget.init({
      public_key: "pk_test_sFAyP_R5YYxWG6Wf6fgHch2mvbyfr35ZH6PtbcIADWQ",
      baseUrl: "https://api.todapay.com/hpp/",
      flow: "iframe",
      selector: "merchant-widget-mount-point",
      amount: {{ $amount }},
      currency: "{{ $currency }}",
      locale: "en",
      description: "This is the testing transaction.",
      reference_id: "",
      expires: "",
      cpi: "",
      service: "payment_card_eur_hpp",
      metadata: {},
      style: {
        theme: "dark"
      },
      display: {
        hide_header: false,
        hide_footer: false,
        hide_progress_bar: false,
        hide_method_filter: false,
        hide_lifetime_counter: false
      }
    });
    </script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>