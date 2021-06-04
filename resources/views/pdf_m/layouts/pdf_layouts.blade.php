<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">       
        /* CLIENT-SPECIFIC STYLES */
        body,
        table,
        td,
        a {
            font-family: sans-serif;
        }


        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
            text-align: left;
        }

        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }

        th{
             color: #272727;
        }
       /* tr:nth-child(even) {
          background-color: #dddddd;
        }*/

        #table th{
            width: 100px;
            background-color: #003281;
            color: #ffffff;
        }

        #table .bg{            
            background-color: #cccccc;
            color: #000000;
        }

        #table td{            
            width: 100px;
        }

        .color_secondary{
            color: #dc3545;
        }

        .color_primary{
            color: #003281;
        }        
    </style>
</head>

<body>  

    @component('pdf_m.layouts.header')
    @endcomponent

    <div>
    @yield('content')
   </div>

</body>

</html>