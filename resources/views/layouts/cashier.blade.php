<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Cashier Dashboard')</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    :root {
      --primary-color: #079CD6;
      --secondary-color: #2F356D;
      --accent-color: #17E8FF;
      --light-bg: rgba(255, 255, 255, 0.9);
    }
    
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .cashier-layout {
      background: linear-gradient(
          137.15deg,
          rgba(23, 232, 255, 0) 0%,
          rgba(23, 232, 255, 0.2) 100%
        ),
        linear-gradient(to left, rgba(7, 156, 214, 0.2), rgba(7, 156, 214, 0.2)),
        linear-gradient(
          119.69deg,
          rgba(93, 141, 230, 0) 0%,
          rgba(142, 176, 239, 0.1) 45.691317319869995%,
          rgba(36, 89, 188, 0.2) 96.88477516174316%
        ),
        linear-gradient(to left, rgba(47, 53, 109, 0.2), rgba(47, 53, 109, 0.2));
      height: 100vh;
      overflow: hidden;
      display: flex;
    }
    
    .sidebar {
      background: rgba(217, 217, 217, 0.7);
      width: 250px;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 100vh;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .sidebar img {
      width: 100%;
      max-width: 150px;
      margin-bottom: 30px;
    }
    
    .sidebar .nav-link {
      color: var(--secondary-color);
      font-size: 1.1rem;
      margin: 12px 0;
      font-weight: 500;
      text-align: center;
      padding: 8px 15px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .sidebar .nav-link:hover {
      background: rgba(7, 156, 214, 0.2);
      color: var(--primary-color);
    }
    
    .sidebar .nav-link.active {
      background: var(--primary-color);
      color: white;
    }
    
    .sidebar .log-out {
      margin-top: auto;
      font-size: 1.1rem;
      color: var(--secondary-color);
      font-weight: 500;
      cursor: pointer;
      padding: 8px 15px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .sidebar .log-out:hover {
      background: rgba(255,0,0,0.1);
      color: #d9534f;
    }
    
    .main-content {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
      background: var(--light-bg);
      border-radius: 15px 0 0 15px;
      box-shadow: -2px 0 10px rgba(0,0,0,0.1);
    }
    
    .page-title {
      font-size: 28px;
      font-weight: 700;
      color: var(--secondary-color);
      margin-bottom: 25px;
      position: relative;
      padding-bottom: 10px;
    }
    
    .page-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 4px;
      background: var(--primary-color);
      border-radius: 2px;
    }
    
    @media (max-width: 768px) {
      .cashier-layout {
        flex-direction: column;
      }
      
      .sidebar {
        width: 100%;
        height: auto;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        padding: 15px;
      }
      
      .sidebar img {
        margin-bottom: 15px;
        max-width: 120px;
      }
      
      .sidebar .nav-link {
        margin: 5px 10px;
        padding: 6px 12px;
      }
      
      .sidebar .log-out {
        margin-top: 0;
        margin-left: auto;
      }
      
      .main-content {
        border-radius: 0;
      }
    }
  </style>
  @stack('styles')
</head>
<body>
  <div class="cashier-layout">
    <div class="sidebar">
      <img src="{{ asset('img/1ds-removebg-preview.png') }}" alt="Company Logo">
      <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Order/Transaction</a>
        <a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">View Laundry</a>
      </nav>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="log-out">Log Out</button>
      </form>
    </div>
    <div class="main-content">
      @yield('content')
    </div>
  </div>

  @yield('modals')

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  @stack('scripts')
</body>
</html>