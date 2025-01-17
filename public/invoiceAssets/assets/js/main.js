(function ($) {
  ("use strict");

  /*--------------------------------------------------------------
 ## Down Load Button Function
   ----------------------------------------------------------------*/
  // $("#download_btn").on("click", function () {
  //   var removable = $("#removable");
  //   removable.remove();
  //   var downloadSection = $("#download_section");
  //   var cWidth = downloadSection.width();
  //   var cHeight = downloadSection.height();
  //   var topLeftMargin = 40;
  //   var pdfWidth = cWidth + topLeftMargin * 2;
  //   var pdfHeight = pdfWidth * 1.5 + topLeftMargin * 2;
  //   var canvasImageWidth = cWidth;
  //   var canvasImageHeight = cHeight;
  //   var totalPDFPages = Math.ceil(cHeight / pdfHeight) - 1;
  //
  //   html2canvas(downloadSection[0], { allowTaint: true }).then(function (
  //     canvas
  //   ) {
  //     canvas.getContext("2d");
  //     var imgData = canvas.toDataURL("image/jpeg", 5.0);
  //     var pdf = new jsPDF("p", "pt", [pdfWidth, pdfHeight]);
  //     pdf.addImage(
  //       imgData,
  //       "JPG",
  //       topLeftMargin,
  //       topLeftMargin,
  //       canvasImageWidth,
  //       canvasImageHeight
  //     );
  //     for (var i = 1; i <= totalPDFPages; i++) {
  //       pdf.addPage(pdfWidth, pdfHeight);
  //       pdf.addImage(
  //         imgData,
  //         "JPG",
  //         topLeftMargin,
  //         -(pdfHeight * i) + topLeftMargin * 0,
  //         canvasImageWidth,
  //         canvasImageHeight
  //       );
  //     }
  //     pdf.save("ivonne-invoice.pdf");
  //   });
  // });


  $("#download_btn").on("click", function () {

    var removable = $("#removable");
    // remove download and print button
    removable.css("display", "none");
    // Show it again after 2 seconds
    setTimeout(function () {
      removable.css("display", "flex");
    }, 2000); // 2000 milliseconds = 2 seconds

    // readjust for desktop
    if (window.innerWidth <= 500) {
      var mobilebanks = $("#mobilebanks");
      var desktopbanks = $("#desktopBanks");
      var totals = $("#totals");

      mobilebanks.css("display", "none");
      desktopbanks.css("display", "block");
      totals.css("width", "33.3333%");
    }

    var downloadSection = $("#download_section");

    // Save original styles
    var originalStyles = {
      width: downloadSection.css("width"),
      height: downloadSection.css("height"),
      overflow: downloadSection.css("overflow"),
    };

    // Set desktop dimensions and ensure full rendering
    var desktopWidth = 1024; // Desktop width in pixels
    var desktopHeight = downloadSection[0].scrollHeight; // Get full height of the content
    downloadSection.css({
      width: desktopWidth + "px",
      height: desktopHeight + "px",
      overflow: "visible", // Ensure the content is fully rendered
    });

    var topLeftMargin = 40;
    var pdfWidth = desktopWidth + topLeftMargin * 2;
    var pdfHeight = pdfWidth * 1.5 + topLeftMargin * 2;
    var canvasImageWidth = desktopWidth;
    var canvasImageHeight = desktopHeight;
    var totalPDFPages = Math.ceil(desktopHeight / pdfHeight);

    html2canvas(downloadSection[0], { allowTaint: true }).then(function (
        canvas
    ) {
      var imgData = canvas.toDataURL("image/jpeg", 1.0);
      var pdf = new jsPDF("p", "pt", [pdfWidth, pdfHeight]);

      pdf.addImage(
          imgData,
          "JPG",
          topLeftMargin,
          topLeftMargin,
          canvasImageWidth,
          canvasImageHeight
      );

      for (var i = 1; i < totalPDFPages; i++) {
        pdf.addPage(pdfWidth, pdfHeight);
        pdf.addImage(
            imgData,
            "JPG",
            topLeftMargin,
            -(pdfHeight * i) + topLeftMargin * 0,
            canvasImageWidth,
            canvasImageHeight
        );
      }

      pdf.save("ivonne-invoice.pdf");

      // Restore original styles
      downloadSection.css(originalStyles);
    });
  });



  $("#copybtn1").on("click", function () {
    let text = document.getElementById("myText1");
    navigator.clipboard.writeText(text.innerHTML);
  });

  /* Copy text */

  $("#copybtn2").on("click", function () {
    let text2 = document.getElementById("myText2");
    navigator.clipboard.writeText(text2.innerText);
  });
})(jQuery); // End of use
