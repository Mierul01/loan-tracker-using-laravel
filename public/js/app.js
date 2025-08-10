// Declare global variables
let currentBalance = 0;
let paymentData = [];
const totalLoan = 8000;

let currentPage = 1;
let rowsPerPage = 10;

// Import Firebase modules
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-analytics.js";
import { getDatabase, ref, set, push, onValue, remove } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-database.js";

// Firebase config
const firebaseConfig = {
  apiKey: "AIzaSyDP0aRFDUc-sJU93f8XGUXOUsDJDbg1-xA",
  authDomain: "loan-tracker-f858d.firebaseapp.com",
  databaseURL: "https://loan-tracker-f858d-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "loan-tracker-f858d",
  storageBucket: "loan-tracker-f858d.appspot.com",
  messagingSenderId: "704407664304",
  appId: "1:704407664304:web:6df3c1b6e73b9be8772d75",
  measurementId: "G-7MZL1QF59L"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const db = getDatabase(app);

// --- Save payment with Cloudinary PDF upload
window.addPayment = async function () {
  const amountInput = document.getElementById("amount");
  const fileInput = document.getElementById("pdfFile");

  const amount = parseFloat(amountInput.value);
  const file = fileInput.files[0];

  if (!amount || amount <= 0 || amount > currentBalance) {
    alert("Invalid amount");
    return;
  }

  const today = new Date().toISOString().split("T")[0];
  let fileUrl = null;

  if (file) {
    const formData = new FormData();
    formData.append("file", file);

    const cloudName = "dsnxduatn";
    const uploadPreset = "unsigned_pdf";

    formData.append("upload_preset", uploadPreset);
    formData.append("resource_type", "raw");

    try {
      const response = await fetch(`https://api.cloudinary.com/v1_1/${cloudName}/raw/upload`, {
        method: "POST",
        body: formData,
      });

      const result = await response.json();
      if (result.secure_url) {
        fileUrl = result.secure_url;
      } else {
        alert("Cloudinary upload failed.");
        console.error(result);
        return;
      }
    } catch (error) {
      console.error("Cloudinary upload error:", error);
      alert("Upload to Cloudinary failed.");
      return;
    }
  }

  const newBalance = currentBalance - amount;
  const entry = {
    date: today,
    amount,
    balance: newBalance,
    fileName: file ? file.name : null,
    fileUrl: fileUrl
  };

  const newPaymentRef = push(ref(db, "payments/"));
  await set(newPaymentRef, entry);

  amountInput.value = "";
  fileInput.value = "";
};

// --- Load and render payment table with pagination
window.onload = function () {
  const paymentTable = document.getElementById("paymentTable");

  onValue(ref(db, "payments"), (snapshot) => {
    paymentData = [];
    let computedBalance = totalLoan;

    const rows = [];
    snapshot.forEach(childSnapshot => {
      const entry = childSnapshot.val();
      const key = childSnapshot.key;
      computedBalance -= parseFloat(entry.amount);
      rows.push({ ...entry, balance: computedBalance, key });
    });

    paymentData = rows.reverse(); // Show latest first
    currentBalance = computedBalance;

    renderPaginatedTable();
    updateFooter();
  });

  // Pagination Event Listeners
  document.getElementById("rowsPerPage").addEventListener("change", function () {
    rowsPerPage = parseInt(this.value);
    currentPage = 1;
    renderPaginatedTable();
  });

  document.getElementById("prevPage").addEventListener("click", function () {
    if (currentPage > 1) {
      currentPage--;
      renderPaginatedTable();
    }
  });

  document.getElementById("nextPage").addEventListener("click", function () {
    if ((currentPage * rowsPerPage) < paymentData.length) {
      currentPage++;
      renderPaginatedTable();
    }
  });
};

// --- Render only the visible rows
function renderPaginatedTable() {
  const paymentTable = document.getElementById("paymentTable");
  paymentTable.innerHTML = "";

  const start = (currentPage - 1) * rowsPerPage;
  const end = start + rowsPerPage;
  const pageData = paymentData.slice(start, end);

  pageData.forEach(entry => {
    const row = paymentTable.insertRow();
    row.insertCell(0).textContent = entry.date;
    row.insertCell(1).textContent = `RM ${entry.amount.toFixed(2)}`;
    row.insertCell(2).textContent = `RM ${entry.balance.toFixed(2)}`;

    // Update pagination button state
    document.getElementById("prevPage").disabled = currentPage === 1;
    document.getElementById("nextPage").disabled = currentPage * rowsPerPage >= paymentData.length;

    const docCell = row.insertCell(3);
    if (entry.fileUrl) {
      const link = document.createElement("a");
      link.href = entry.fileUrl;
      link.textContent = entry.fileName || "View File";
      link.target = "_blank";
      docCell.appendChild(link);
    } else {
      docCell.textContent = "-";
    }

    const deleteCell = row.insertCell(4);
    const deleteBtn = document.createElement("button");
    deleteBtn.innerHTML = "🗑️";
    deleteBtn.style.cursor = "pointer";
    deleteBtn.style.background = "none";
    deleteBtn.style.border = "none";
    deleteBtn.title = "Delete this payment";
    deleteBtn.onclick = async () => {
      if (confirm("Delete this payment entry?")) {
        await remove(ref(db, "payments/" + entry.key));
      }
    };
    deleteCell.appendChild(deleteBtn);
  });

  document.getElementById("pageInfo").textContent = `Page ${currentPage}`;
}

// --- Update footer balance info
function updateFooter() {
  document.getElementById("tableTotalLoan").textContent = `RM ${totalLoan.toFixed(2)}`;
  document.getElementById("tableCurrentBalance").textContent = `RM ${currentBalance.toFixed(2)}`;
}

// --- Reset payment history
window.resetAll = function () {
  if (confirm("Are you sure you want to clear all payment history?")) {
    set(ref(db, "payments"), null);
    paymentData = [];
    currentBalance = totalLoan;
    renderPaginatedTable();
    updateFooter();
  }
};

// --- Optional fallback if you still use top text display
window.renderTable = function () {
  document.getElementById("currentBalance").textContent =
    `Current Balance: RM ${currentBalance.toFixed(2)}`;
};
