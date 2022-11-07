let showLoading = () => {
  if ($(".loading").length === 0) {
    $("body").append(`<div class="loading">
    <div class="load">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
    </div>
  </div>`);
  }

  $(".loading").css("display", "block");
  $("body").css("overflow", "hidden");
};

let hideLoading = () => {
  $(".loading").css("display", "none");
  $("body").css("overflow", "visible");
};
