<?php
require_once 'config.php'; // must set $conn (mysqli)

// Fetch rows
$sql = "SELECT * FROM customer_orders ORDER BY cus_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>View Customers</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
  *{box-sizing:border-box;margin:0;padding:0;font-family:'Poppins',sans-serif}
  html,body{height:100%;background:linear-gradient(120deg,#001F3F,#003366,#004C99);background-size:400% 400%;animation:gradientBG 15s ease infinite;color:#fff}
  @keyframes gradientBG{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}

  nav{position:sticky;top:0;width:100%;background:rgba(0,31,63,0.85);display:flex;justify-content:space-between;align-items:center;padding:15px 28px;backdrop-filter:blur(10px);box-shadow:0 2px 15px rgba(0,230,230,0.3);z-index:10}
  nav .logo{width:44px}
  nav ul{list-style:none;display:flex;gap:20px}
  nav a{color:#00e6e6;text-decoration:none;font-weight:500}
  nav a:hover{color:#ffff00;text-shadow:0 0 6px #ffd700}

  main{padding:72px 20px 40px;display:flex;flex-direction:column;align-items:center;min-height:calc(100vh - 160px)}
  h1{font-size:2.4rem;margin-bottom:28px;color:#ffd700;text-shadow:0 0 8px #ffd700}

  .search-box{width:100%;max-width:520px;margin-bottom:22px}
  .search-box input{width:100%;padding:12px 16px;border-radius:30px;border:none;background:rgba(0,0,0,0.32);color:#fff;box-shadow:0 0 10px #ffd700}

  /* Table wrapper for centering and responsive horizontal scroll */
  .table-wrap{width:95%;max-width:1150px;margin:0 auto;overflow:hidden;border-radius:12px;box-shadow:0 0 25px rgba(255,217,0,0.15);background:rgba(255,255,255,0.04);backdrop-filter:blur(12px)}

  table.customer-table{width:100%;border-collapse:collapse;table-layout:fixed}
  table.customer-table thead th{background:rgba(0,119,255,0.90);color:#000;padding:12px 14px;font-weight:700;text-align:left;font-size:0.92rem}
  table.customer-table tbody td{padding:12px 14px;border-top:1px solid rgba(255,255,255,0.04);vertical-align:middle; /* important for button alignment */ font-size:0.95rem;color:#f1f1f1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}

  /* column width tuning */
  .col-name{width:18%}
  .col-shipper{width:14%}
  .col-dest{width:14%}
  .col-status{width:12%}
  .col-bl{width:12%}
  .col-email{width:20%}
  .col-actions{width:10%;text-align:center}

  tr:hover td{background:rgba(255,255,0,0.05);transform:none}

  /* Action buttons container ensures consistent alignment */
  .action-buttons{display:inline-flex;gap:8px;align-items:center;justify-content:center}
  .action-buttons .btn{display:inline-flex;align-items:center;gap:8px;padding:8px 10px;border-radius:8px;border:none;font-weight:600;cursor:pointer;font-size:0.85rem}
  .btn i{font-size:0.95rem}
  .btn-edit{background:#FFFF00;color:#000}
  .btn-edit:hover{background:#FFD700;color:#fff}
  .btn-delete{background:#FF4500;color:#fff}
  .btn-delete:hover{background:#FF0000;box-shadow:0 0 8px rgba(255,99,71,0.5)}

  /* small screen: allow horizontal scroll and smaller paddings */
  @media(max-width:900px){
    .col-name,.col-email{width:auto}
    table.customer-table thead th, table.customer-table tbody td{padding:10px}
    .action-buttons .btn{padding:7px 8px;font-size:0.8rem}
  }
  @media(max-width:600px){
    .table-wrap{overflow:auto}
    table.customer-table{min-width:900px}
  }

  footer{margin-top:30px;padding:18px 20px;background:rgba(0,31,63,0.85);text-align:center;color:#00e6e6}
</style>
</head>
<body>

<nav>
  <img src="logo.png" alt="CSC Logo" class="logo">
  <ul>
    <li><a href="menu.php">Menu</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<main>
  <h1>Customer Records</h1>

  <div class="search-box">
    <input id="searchInput" type="text" placeholder="Search customer..." />
  </div>

  <div class="table-wrap">
    <table class="customer-table" id="customerTable" role="table" aria-label="Customer orders">
      <thead>
        <tr>
          <th class="col-name">Name</th>
          <th class="col-shipper">Shipper</th>
          <th class="col-dest">Destination</th>
          <th class="col-status">Status</th>
          <th class="col-bl">B/L No</th>
          <th class="col-email">Email</th>
          <th class="col-actions">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="col-name"><?= htmlspecialchars($row['customer_name']); ?></td>
              <td class="col-shipper"><?= htmlspecialchars($row['shipper_name']); ?></td>
              <td class="col-dest"><?= htmlspecialchars($row['destination']); ?></td>
              <td class="col-status"><?= htmlspecialchars($row['status']); ?></td>
              <td class="col-bl"><?= htmlspecialchars($row['bl_no']); ?></td>
              <td class="col-email"><?= htmlspecialchars($row['email']); ?></td>
              <td class="col-actions">
                <div class="action-buttons">
                  <button class="btn btn-edit" type="button" onclick="location.href='edit_customer.php?id=<?= (int)$row['cus_id']; ?>'">
                    <i class="fas fa-edit"></i><span class="sr-only">Edit</span>
                  </button>
                  <button class="btn btn-delete" type="button" onclick="if(confirm('Delete this record?')) location.href='delete_customer.php?id=<?= (int)$row['cus_id']; ?>'">
                    <i class="fas fa-trash"></i><span class="sr-only">Delete</span>
                  </button>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" style="text-align:center;padding:20px;color:#ffd700">No customer orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<footer>
  &copy; 2025 CSC Shipping System |
  <a href="#">Facebook</a> |
  <a href="#">Twitter</a>
</footer>

<script>
  // client-side search
  const searchInput = document.getElementById('searchInput');
  const table = document.getElementById('customerTable').tBodies[0];

  searchInput.addEventListener('input', () => {
    const q = searchInput.value.trim().toLowerCase();
    Array.from(table.rows).forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(q) ? '' : 'none';
    });
  });
</script>

</body>
</html>
<?php $conn->close(); ?>
