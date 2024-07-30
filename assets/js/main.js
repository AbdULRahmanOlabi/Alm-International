document.addEventListener("DOMContentLoaded", () => {
  (function () {
    "use strict";

    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
      el = el.trim();
      if (all) {
        return [...document.querySelectorAll(el)];
      } else {
        return document.querySelector(el);
      }
    };

    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
      let selectEl = select(el, all);
      if (selectEl) {
        if (all) {
          selectEl.forEach((e) => e.addEventListener(type, listener));
        } else {
          selectEl.addEventListener(type, listener);
        }
      }
    };

    /**
     * Easy on scroll event listener
     */
    const onscroll = (el, listener) => {
      el.addEventListener("scroll", listener);
    };

    /**
     * Navbar links active state on scroll
     */
    let navbarlinks = select("#navbar .scrollto", true);
    const navbarlinksActive = () => {
      let position = window.scrollY + 200;
      navbarlinks.forEach((navbarlink) => {
        if (!navbarlink.hash) return;
        let section = select(navbarlink.hash);
        if (!section) return;
        if (
          position >= section.offsetTop &&
          position <= section.offsetTop + section.offsetHeight
        ) {
          navbarlink.classList.add("active");
        } else {
          navbarlink.classList.remove("active");
        }
      });
    };
    window.addEventListener("load", navbarlinksActive);
    onscroll(document, navbarlinksActive);

    /**
     * Scrolls to an element with header offset
     */
    const scrollto = (el) => {
      let header = select("#header");
      let offset = header.offsetHeight;

      if (!header.classList.contains("header-scrolled")) {
        offset -= 16;
      }

      let elementPos = select(el).offsetTop;
      window.scrollTo({
        top: elementPos - offset,
        behavior: "smooth",
      });
    };

    /**
     * Toggle .header-scrolled class to #header when page is scrolled
     */
    let selectHeader = select("#header");
    if (selectHeader) {
      const headerScrolled = () => {
        if (window.scrollY > 100) {
          selectHeader.classList.add("header-scrolled");
        } else {
          selectHeader.classList.remove("header-scrolled");
        }
      };
      window.addEventListener("load", headerScrolled);
      onscroll(document, headerScrolled);
    }

    /**
     * Back to top button
     */
    let backtotop = select(".back-to-top");
    if (backtotop) {
      const toggleBacktotop = () => {
        if (window.scrollY > 100) {
          backtotop.classList.add("active");
        } else {
          backtotop.classList.remove("active");
        }
      };
      window.addEventListener("load", toggleBacktotop);
      onscroll(document, toggleBacktotop);
    }

    /**
     * Mobile nav toggle
     */
    on("click", ".mobile-nav-toggle", function (e) {
      select("#navbar").classList.toggle("navbar-mobile");
      this.classList.toggle("bi-list");
      this.classList.toggle("bi-x");
    });

    /**
     * Mobile nav dropdowns activate
     */
    on(
      "click",
      ".navbar .dropdown > a",
      function (e) {
        if (select("#navbar").classList.contains("navbar-mobile")) {
          e.preventDefault();
          this.nextElementSibling.classList.toggle("dropdown-active");
        }
      },
      true
    );

    /**
     * Scroll with ofset on links with a class name .scrollto
     */
    on(
      "click",
      ".scrollto",
      function (e) {
        if (select(this.hash)) {
          e.preventDefault();

          let navbar = select("#navbar");
          if (navbar.classList.contains("navbar-mobile")) {
            navbar.classList.remove("navbar-mobile");
            let navbarToggle = select(".mobile-nav-toggle");
            navbarToggle.classList.toggle("bi-list");
            navbarToggle.classList.toggle("bi-x");
          }
          scrollto(this.hash);
        }
      },
      true
    );

    /**
     * Scroll with ofset on page load with hash links in the url
     */
    window.addEventListener("load", () => {
      if (window.location.hash) {
        if (select(window.location.hash)) {
          scrollto(window.location.hash);
        }
      }
    });

    /**
     * Porfolio isotope and filter
     */
    window.addEventListener("load", () => {
      let portfolioContainer = select(".portfolio-container");
      if (portfolioContainer) {
        let portfolioIsotope = new Isotope(portfolioContainer, {
          itemSelector: ".portfolio-item",
        });

        let portfolioFilters = select("#portfolio-flters li", true);

        on(
          "click",
          "#portfolio-flters li",
          function (e) {
            e.preventDefault();
            portfolioFilters.forEach(function (el) {
              el.classList.remove("filter-active");
            });
            this.classList.add("filter-active");

            portfolioIsotope.arrange({
              filter: this.getAttribute("data-filter"),
            });
          },
          true
        );
      }
    });

    /**
     * Initiate portfolio lightbox
     */
    const portfolioLightbox = GLightbox({
      selector: ".portfolio-lightbox",
    });

    /**
     * Portfolio details slider
     */
    new Swiper(".portfolio-details-slider", {
      speed: 400,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        type: "bullets",
        clickable: true,
      },
    });

    /**
     * Initiate Pure Counter
     */
    new PureCounter();

    /**
     * English Form submission handling
     */
    function submitEnglishForm(event) {
      event.preventDefault();

      // Get reCAPTCHA token
      const recaptchaResponse = grecaptcha.getResponse();

      if (!recaptchaResponse) {
        alert("Please complete the reCAPTCHA.");
        return;
      }

      const formData = {
        firstName: document.querySelector("#firstName").value,
        lastName: document.querySelector("#lastName").value,
        country: document.querySelector("#country").value,
        dob: document.querySelector("#dob").value,
        fatherName: document.querySelector("#fatherName").value,
        class: document.querySelector("#class").value,
        "g-recaptcha-response": recaptchaResponse,
      };

      fetch("api_register_sql_server.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          alert(data.message);
          // Optionally reset the form after successful submission
          document.getElementById("registrationFormEn").reset();
          grecaptcha.reset();
        })
        .catch((error) => {
          console.error(
            "There was an error processing your registration:",
            error
          );
          alert("There was an error processing your registration.");
        });
    }

    /**
     * Arabic Form submission handling
     */
    function submitArabicForm(event) {
      event.preventDefault();

      // Get reCAPTCHA token
      const recaptchaResponse = grecaptcha.getResponse();

      if (!recaptchaResponse) {
        alert("يرجى إكمال اختبار reCAPTCHA.");
        return;
      }

      const formData = {
        firstName: document.querySelector("#firstName").value,
        lastName: document.querySelector("#lastName").value,
        country: document.querySelector("#country").value,
        dob: document.querySelector("#dob").value,
        fatherName: document.querySelector("#fatherName").value,
        class: document.querySelector("#class").value,
        "g-recaptcha-response": recaptchaResponse, // Include reCAPTCHA response
      };

      fetch("api_register_sql_server_ar.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("استجابة الشبكة لم تكن جيدة");
          }
          return response.json();
        })
        .then((data) => {
          alert(data.message);
          // Optionally reset the form after successful submission
          document.getElementById("registrationFormAr").reset();
          grecaptcha.reset();
        })
        .catch((error) => {
          console.error("حدث خطأ أثناء معالجة تسجيلك:", error);
          alert("حدث خطأ أثناء معالجة تسجيلك.");
        });
    }

    // Attach submit event to the forms
    let englishForm = document.querySelector("#student-registration");
    let arabicForm = document.querySelector("#student-registration-ar");

    if (englishForm) {
      englishForm.addEventListener("submit", submitEnglishForm);
    }

    if (arabicForm) {
      arabicForm.addEventListener("submit", submitArabicForm);
    }
  })();
});
