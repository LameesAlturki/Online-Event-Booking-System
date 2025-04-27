  <!DOCTYPE html>

  <head>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
  </head>
  <style>
    /*admin login*/
    .login-page {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .login-form {
      width: 100%;
      max-width: 400px;
      padding: 32px;
      background: #fdf6e3;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);

    }

    .login-form h2 {
      font-size: 2rem;
      color: #3e2723;
      margin-bottom: 24px;

    }

    .btn-primary {
      background-color: #8d6e63;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 12px;
      font-weight: bold;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
      text-decoration: none;
    }

    .btn-primary:hover {
      background-color: #6d4c41;
    }

    body,
    html {
      font-family: "Inconsolata", monospace;
      margin: 0;
    }

    .admin-container {
      display: flex;
      background-color: #fdf6e3;
    }

    /*manage event*/
    .w3-table td a {
      margin-right: 8px;
      color: #795548;
      text-decoration: none;
    }

    /*view event */
    .main-content {
      flex: 1;
      padding: 32px;
      margin-left: 220px;
    }

    .event-details {
      flex-direction: column;
      align-items: center;
      padding: 15px;
      flex-grow: 1;
      display: flex;
      flex-wrap: wrap;
      margin-top: 24px;
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .event-image img {
      max-width: 320px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .event-info {
      flex: 1;
    }

    .event-info h2 {
      margin-bottom: 16px;
      color: #5d4037;
    }

    .detail-item {
      margin-bottom: 12px;
    }

    .label {
      font-weight: bold;
      color: #6d4c41;
      margin-right: 8px;
    }

    .action-buttons a,
    .action-buttons button {
      display: inline-block;
      margin-right: 12px;
      padding: 12px 24px;
      border-radius: 12px;
      font-weight: bold;
      text-decoration: none;
      font-size: 16px;
    }

    .btn-edit {
      background-color: #8d6e63;
      color: white;
    }

    .btn-edit:hover {
      background-color: #6d4c41;
    }

    .btn-back {
      background-color: #d7ccc8;
      color: #3e2723;
    }

    .btn-back:hover {
      background-color: #bcaaa4;
    }

    h1 {
      font-size: 2.5rem;
      color: #3e2723;
    }

    /*edit event */
    .form-group img.thumbnail {
      max-width: 200px;
      border-radius: 8px;
    }

    /*delete event */
    .error-message,
    .warning-message {
      background-color: #f8d7da;
      color: #721c24;
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 24px;
    }

    .warning-message {
      background-color: #fff3cd;
      color: #856404;
    }

    .btn-delete {
      background-color: #e57373;
      color: white;
    }

    .btn-delete:hover {
      background-color: #d32f2f;
    }

    .btn-secondary {
      background-color: #d7ccc8;
      color: #3e2723;
    }

    .btn-secondary:hover {
      background-color: #bcaaa4;
    }

    /*add event */
    .form-group {
      margin-bottom: 24px;
    }

    .form-group label {
      font-weight: bold;
      color: #6d4c41;
      display: block;
      margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 8;
      background-color: white;
      box-sizing: border-box;
    }

    .form-group input[type="file"] {
      padding: 6px;
    }

    .form-group textarea {
      resize: vertical;
      min-height: 80px;
    }

    .btn {
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
      margin-top: 16px;
      border: none;
    }

    .btn-add {
      background-color: #4CAF50;
      color: white;
    }

    .btn-add:hover {
      background-color: #388e3c;
    }

    .error-message ul {
      list-style: none;
      padding: 0;
    }

    .error-message li {
      margin-bottom: 8px;
    }

    /*view booking*/
    .booking-table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .booking-table th,
    .booking-table td {
      padding: 16px;
      text-align: left;
    }

    .booking-table th {
      background-color: #8d6e63;
      color: white;
    }

    .booking-table tr:nth-child(even) {
      background-color: #f5f5f5;
    }

    .booking-table tr:hover {
      background-color: #eceff1;
    }

    p {
      font-size: 1.2rem;
      color: #5d4037;
    }

    /*home */
    .site-header,
    .site-footer {
      background-color: #3e2723;
      color: white;
    }

    .site-footer p {
      margin: 0;
    }

    .cart-count {
      background-color: red;
      color: white;
      padding: 2px 6px;
      border-radius: 50%;
      font-size: 12px;
      vertical-align: top;
      margin-left: 4px;
    }

    .events-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
      width: 100%;
    }

    .event-card {
      display: flex;
      flex-direction: column;
      height: 100%;
      transition: box-shadow 0.3s;
      position: relative;
      border-radius: 12px;
      overflow: hidden;
    }


    .event-imageh img {
      width: 100%;
      height: auto;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
      line-height: 0;
    }

    .event-details {
      margin-top: 0;
    }

    .btn-bookNow {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      text-align: center;
      border: none;
      border-radius: 0 0 12px 12px;
      text-decoration: none;
      opacity: 0;
      transform: translateY(10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
      position: absolute;
      bottom: 0;
      left: 0;
    }

    .event-card:hover .btn-bookNow {
      opacity: 1;
      transform: translateY(0);
    }

    /* Responsive grid */
    @media (min-width: 601px) and (max-width: 992px) {
      .events-grid {
        grid-template-columns: repeat(2, 1fr);
        /* 2 cards per row for tablets */
      }
    }

    @media (min-width: 993px) {
      .events-grid {
        grid-template-columns: repeat(3, 1fr);
        /* 3 cards per row for monitors */
      }
    }

    /*event*/
    .main-content-info {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .success-message {
      background-color: #4CAF50;
      color: white;
      padding: 15px;
      margin: 15px 0;
      border-radius: 5px;
      font-weight: bold;
      font-size: 1.1em;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      position: relative;
      animation: fadeInAndBounce 0.5s ease-out;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .success-message::before {
      content: "✓";
      font-size: 1.3em;
      margin-right: 10px;
      background-color: white;
      color: #4CAF50;
      width: 25px;
      height: 25px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      line-height: 1;
    }

    .view-cart-link {
      color: white;
      text-decoration: underline;
      margin-left: 5px;
      font-weight: bold;
    }

    .view-cart-link:hover {
      color: #ffff99;
    }
  </style>