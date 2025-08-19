const chatIcon = document.getElementById("chat-icon");
const chatWindow = document.getElementById("chat-window");
const header = chatWindow.querySelector(".header");
const input = chatWindow.querySelector("input");
const button = chatWindow.querySelector("button");
const body = chatWindow.querySelector(".body");

let offsetX = 0;
let offsetY = 0;
let isDragging = false;
let wasDragged = false;
let draggingElement = null;

// --------- 拖曳功能 ----------
function clamp(value, min, max) {
  return Math.max(min, Math.min(max, value));
}

function syncChatWindowToIcon() {
  const iconRect = chatIcon.getBoundingClientRect();
  const wrapperRect = chatIcon.offsetParent.getBoundingClientRect(); // 相對外層 chat-container

  const chatWidth = chatWindow.offsetWidth || 300;
  const left = iconRect.right - wrapperRect.left - chatWidth;
  const top = iconRect.bottom - wrapperRect.top + 10;

  chatWindow.style.left = `${left}px`;
  chatWindow.style.top = `${top}px`;
}

window.onload = () => {
  syncChatWindowToIcon();

  // ✅ 監控 chatWindow 是否被手動 resize，若有則重新對齊
  const resizeObserver = new ResizeObserver(() => {
    syncChatWindowToIcon();
  });
  resizeObserver.observe(chatWindow);
};

window.addEventListener("resize", syncChatWindowToIcon);

function enableDrag(element) {
  element.addEventListener("mousedown", (e) => {
    isDragging = true;
    wasDragged = false;
    draggingElement = element;
    offsetX = e.clientX - element.offsetLeft;
    offsetY = e.clientY - element.offsetTop;
    e.preventDefault();
  });
}

document.addEventListener("mousemove", (e) => {
  if (isDragging && draggingElement) {
    wasDragged = true;
    let x = e.clientX - offsetX;
    let y = e.clientY - offsetY;

    x = clamp(x, 0, window.innerWidth - draggingElement.offsetWidth);
    y = clamp(y, 0, window.innerHeight - draggingElement.offsetHeight);

    draggingElement.style.left = `${x}px`;
    draggingElement.style.top = `${y}px`;

    syncChatWindowToIcon(); // 如果 chatWindow 要跟著 icon 動
  }
});

document.addEventListener("mouseup", () => {
  isDragging = false;
  draggingElement = null;
});

// --------- Chat Icon 開關 ----------
chatIcon.addEventListener("click", () => {
  if (wasDragged) {
    wasDragged = false;
    return;
  }

  const isHidden =
    chatWindow.style.visibility === "hidden" ||
    chatWindow.style.visibility === "";
  chatWindow.style.visibility = isHidden ? "visible" : "hidden";
  chatWindow.style.opacity = isHidden ? "1" : "0";

  if (isHidden) {
    syncChatWindowToIcon(); // ✅ 每次開啟時重新定位
  }
});
let resizeTimeout;
window.addEventListener("resize", () => {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(syncChatWindowToIcon, 150);
});

enableDrag(chatIcon);

// --------- 訊息處理 ----------
function appendMessage(text, sender = "user") {
  const messageDiv = document.createElement("div");
  messageDiv.classList.add("message", sender);

  if (sender === "bot") {
    messageDiv.innerHTML = marked.parse(text); // markdown support
  } else {
    messageDiv.textContent = text;
  }

  body.appendChild(messageDiv);
  body.scrollTop = body.scrollHeight;
}

async function sendMessage() {
  const text = input.value.trim();
  if (!text) return;

  appendMessage(text, "user");
  input.value = "";

  // ✅ 只要包含「健康數據」就觸發
  /* if (text.includes('健康數據')) {
    const targetBtn = document.querySelector('button[data-bs-target="#addDataModal"]');
    if (targetBtn) {
      targetBtn.click(); // 觸發 click 事件
    } else {
      console.warn('找不到新增健康數據的按鈕');
    }
    return; // 不再呼叫 AI 回覆
  } */

  try {
    const response = await fetch("http://localhost:3001/api/chat", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        messages: [{ role: "user", content: text }],
      }),
    });

    const data = await response.json();
    const aiReply = data.choices?.[0]?.message?.content || "❌ 無法取得回覆";

    // ✅ 特殊指令處理：開啟健康數據 modal
    if (aiReply === "__trigger_modal_addData__") {
      const modalBtn = document.querySelector(
        'button[data-bs-target="#addDataModal"]'
      );
      if (modalBtn) {
        modalBtn.click(); // 直接觸發 modal 開啟
      } else {
        console.warn("找不到新增健康數據的按鈕");
      }
      return; // 不顯示訊息
    }

    // 🟢 一般訊息照常顯示
    appendMessage(aiReply, "bot");
  } catch (err) {
    console.error("錯誤：", err);
    appendMessage("❌ 發送失敗，請稍後再試", "bot");
  }
}

// --------- 發送事件 ----------
button.addEventListener("click", sendMessage);
input.addEventListener("keydown", (e) => {
  if (e.key === "Enter") sendMessage();
});
