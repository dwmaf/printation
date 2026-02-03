<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">

  <style>
    html, body {
      margin: 0;
      height: 100%;
      background: #111827;
      overflow: hidden;
      touch-action: pan-y;
    }

    .stage {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      user-select: none;
      -webkit-user-select: none;
    }

    canvas {
      background: white;
      max-width: 100%;
      max-height: 100%;
      border-radius: 12px;
      box-shadow: 0 15px 45px rgba(0,0,0,.45);
    }

    .hint {
      position: fixed;
      top: 12px;
      left: 50%;
      transform: translateX(-50%);
      font: 800 12px system-ui;
      color: rgba(255,255,255,.9);
      background: rgba(0,0,0,.35);
      padding: 6px 16px;
      border-radius: 999px;
      pointer-events: none;
      z-index: 9999;
    }
  </style>
</head>

<body>

  <div class="stage" id="stage">
    <div class="hint" id="hint">Loading PDF...</div>
    <canvas id="cv"></canvas>
  </div>


  <!-- PDF.JS MODULE LOCAL -->
  <script type="module">
    import * as pdfjsLib from "/pdfjs/pdf.mjs";

    pdfjsLib.GlobalWorkerOptions.workerSrc = "/pdfjs/pdf.worker.mjs";

    const url = @json($fileUrl);

    const canvas = document.getElementById("cv");
    const ctx = canvas.getContext("2d");
    const hint = document.getElementById("hint");
    const stage = document.getElementById("stage");

    let pdfDoc = null;
    let pageNum = 1;
    let totalPages = 1;


    // --- FIT PAGE FULL SCREEN ---
    function fitScale(viewport) {
      const w = window.innerWidth;
      const h = window.innerHeight;

      const scaleX = w / viewport.width;
      const scaleY = h / viewport.height;

      return Math.min(scaleX, scaleY) * 0.97;
    }


    // --- RENDER PAGE ---
    async function renderPage() {
      if (!pdfDoc) return;

      const page = await pdfDoc.getPage(pageNum);

      const vp1 = page.getViewport({ scale: 1 });
      const scale = fitScale(vp1);
      const viewport = page.getViewport({ scale });

      canvas.width = Math.floor(viewport.width);
      canvas.height = Math.floor(viewport.height);

      await page.render({
        canvasContext: ctx,
        viewport
      }).promise;

      hint.textContent = `${pageNum} / ${totalPages}`;
    }


    // --- NEXT / PREV ---
    function nextPage() {
      if (pageNum < totalPages) {
        pageNum++;
        renderPage();
      }
    }

    function prevPage() {
      if (pageNum > 1) {
        pageNum--;
        renderPage();
      }
    }


    // =====================================================
    // ✅ TOUCH SWIPE SUPPORT (HP)
    // =====================================================
    let startX = 0;
    let startY = 0;

    stage.addEventListener("touchstart", (e) => {
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
    });

    stage.addEventListener("touchend", (e) => {
      const endX = e.changedTouches[0].clientX;
      const endY = e.changedTouches[0].clientY;

      const dx = endX - startX;
      const dy = endY - startY;

      if (Math.abs(dy) > 80) return;

      if (dx < -60) nextPage(); // swipe kiri
      if (dx > 60) prevPage();  // swipe kanan
    });


    // =====================================================
    // ✅ MOUSE DRAG SUPPORT (Laptop)
    // =====================================================
    let mouseDown = false;
    let mouseStartX = 0;

    stage.addEventListener("mousedown", (e) => {
      mouseDown = true;
      mouseStartX = e.clientX;
    });

    stage.addEventListener("mouseup", (e) => {
      if (!mouseDown) return;
      mouseDown = false;

      const dx = e.clientX - mouseStartX;

      if (dx < -80) nextPage(); // drag kiri
      if (dx > 80) prevPage();  // drag kanan
    });


    // =====================================================
    // ✅ SCROLL WHEEL SUPPORT
    // =====================================================
    stage.addEventListener("wheel", (e) => {
      if (e.deltaY > 0) nextPage();
      if (e.deltaY < 0) prevPage();
    });


    // =====================================================
    // ✅ KEYBOARD SUPPORT
    // =====================================================
    window.addEventListener("keydown", (e) => {
      if (e.key === "ArrowRight") nextPage();
      if (e.key === "ArrowLeft") prevPage();
    });


    // =====================================================
    // AUTO RESIZE
    // =====================================================
    window.addEventListener("resize", () => {
      renderPage();
    });


    // =====================================================
    // LOAD PDF
    // =====================================================
    pdfjsLib.getDocument(url).promise
      .then((doc) => {
        pdfDoc = doc;
        totalPages = doc.numPages;
        renderPage();
      })
      .catch((err) => {
        console.error("PDF ERROR:", err);
        hint.textContent = "PDF gagal dimuat!";
      });

  </script>

</body>
</html>
