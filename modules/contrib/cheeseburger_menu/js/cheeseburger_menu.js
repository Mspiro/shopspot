(function (Drupal, $, _) {
  Drupal.behaviors.cheeseburgermenuMain = {
    attach: function (context) {
      var TRIGGER = "data-cheeseburger-id";
      var MENU_TRIGGER = `.block-cheeseburgermenu__trigger-element, .block-cheeseburgermenu__trigger-element span`;
      var BACKDROP = ".cheeseburger-menu__backdrop";
      var BACKDROP_ACTIVE = `${BACKDROP}--active`;
      var BODY_ACTIVE = "body--has-active-cheese";
      var CHEESEBURGER_SIDEMENU = ".cheeseburger-menu__side-menu";
      var CHEESEBURGER_MAINMENU = ".cheeseburger-menu__main-navigation-area";
      var SIDE_TRIGGER = `${CHEESEBURGER_SIDEMENU} [${TRIGGER}]`;
      var MENU_CLOSE = "[data-cheeseburger-close]";
      var PARENT_TRIGGER = "[data-cheeseburger-parent-trigger]";
      var DEFAULT_EXPAND = "[data-cheeseburger-default-expand]";
      var CHEESE_HIGHLIGHTED = ".highlighted";
      var CHEESEBURGER = ".block-cheeseburgermenu-container";
      var CHEESEBURGER_ACTIVE = `${CHEESEBURGER}--is-open`;
      var CHEESEBURGER_MENU_ITEM = ".cheeseburger-menu__item";
      var CHEESEBURGER_MENU_ITEM_ACTIVE = `${CHEESEBURGER_MENU_ITEM}--is-expanded`;
      var CHEESEBIRGER_PARENT = ".cheeseburger-parent";
      var COLLAPSIBLE_TITLE = ".cheeseburger-menu__title--collapsible";
      var EXPANDED_TITLE = ".cheeseburger-menu__title--expanded";
      var MENU_VISIBLE = "cheeseburger-menu__mainmenu--visible";
      var MENU_INVISIBLE = "cheeseburger-menu__mainmenu--invisible";
      var MAIN_MENU = ".cheeseburger-menu__mainmenu";

      var ANIMATION_SPEED = 300;

      $(MENU_TRIGGER, context)
        .once()
        .each(function () {
          $(this).on("click", handleTriggerClick);
        });

      $(MENU_CLOSE, context).each(function () {
        $(this).on("click", handleCloseClick);
      });

      $(PARENT_TRIGGER, context).each(function () {
        $(this).on("click", handleParentTriggerClick);
      });

      $(SIDE_TRIGGER, context).each(function () {
        $(this).on("mousedown", handleSideTriggerClick);
        $(this).on("mouseup", handleSideTriggerMouseUp);
      });

      $(SIDE_TRIGGER, context).each(function () {
        $(this).on("touchstart", handleSideTriggerClick);
        $(this).on("touchend", handleSideTriggerMouseUp);
      });

      $(CHEESEBURGER, context).each(function () {
        $(this).on("scroll", _.throttle(handleCheeseScroll, 50));
        $(this).on("scroll", _.debounce(handleCheeseScroll, 10));
      });

      $(COLLAPSIBLE_TITLE, context)
        .once()
        .each(function () {
          $(this).children(MAIN_MENU).addClass(MENU_INVISIBLE);
          $(this)
            .children(".cheeseburger-menu__title")
            .on("click", handleTitleTriggerClick);
        });

      $(EXPANDED_TITLE, context)
        .each(function () {
          $(this)
            .children(MAIN_MENU)
            .addClass(MENU_VISIBLE)
            .removeClass(MENU_INVISIBLE);
        });

      $("body", context).on("click", handleMiscClick);

      $(document, context).on("keydown", function (e) {
        if (e.key === "Escape") {
          handleCloseClick(e);
        }
      });

      $(document).ready(function () {
        injectBackdrop();
        openParents();
      });

      function openParents() {
        $(CHEESEBIRGER_PARENT).each(function () {
          if ($(this).hasClass("in-active-trail")) {
            $(this).addClass("cheeseburger-menu__item--is-expanded");
          }
        });
      }

      function injectBackdrop() {
        if ($(BACKDROP).length === 0) {
          var backdrop = document.createElement("div");
          backdrop.setAttribute("class", BACKDROP.replace(".", ""));

          $("body").prepend(backdrop);
        }
      }

      function handleTriggerClick(e) {
        e.preventDefault();
        e.stopPropagation();

        var id =
          $(e.target).attr(TRIGGER) || $(e.target).parent().attr(TRIGGER);
        if ($(`#${id}`).attr("style")) {
          $(`#${id}`).removeAttr("style");
          setTimeout(function () {
            $(`#${id}`).toggleClass(CHEESEBURGER_ACTIVE.replace(".", ""));
            $(BACKDROP).toggleClass(BACKDROP_ACTIVE.replace(".", ""));
            $("body").toggleClass(BODY_ACTIVE.replace(".", ""));
          }, 1);
        } else {
          $(`#${id}`).toggleClass(CHEESEBURGER_ACTIVE.replace(".", ""));
          $(BACKDROP).toggleClass(BACKDROP_ACTIVE.replace(".", ""));
          $("body").toggleClass(BODY_ACTIVE.replace(".", ""));
        }
      }

      function handleCloseClick(e) {
        e.preventDefault();
        e.stopPropagation();
        $(CHEESEBURGER_ACTIVE)
          .eq(0)
          .removeClass(CHEESEBURGER_ACTIVE.replace(".", ""));
        $(BACKDROP).removeClass(BACKDROP_ACTIVE.replace(".", ""));
        $("body").removeClass(BODY_ACTIVE.replace(".", ""));
      }

      function handleParentTriggerClick(e) {
        e.preventDefault();
        e.stopPropagation();

        var $parentItem = $(e.target).parents(CHEESEBURGER_MENU_ITEM).eq(0);

        if (
          $parentItem.hasClass(CHEESEBURGER_MENU_ITEM_ACTIVE.replace(".", ""))
        ) {
          // Toggle already opened submenu.
          $.each($parentItem.children("ul"), function (i) {
            animateToHeightAndClearStyle(
              $(this),
              0,
              $parentItem.children("ul").length
            );
          });

          setTimeout(function () {
            $parentItem.removeClass(
              CHEESEBURGER_MENU_ITEM_ACTIVE.replace(".", "")
            );
          }, ANIMATION_SPEED * 0.8);
        } else {
          // Open selected submenu and close sibling submenus.
          $parentItem.addClass(CHEESEBURGER_MENU_ITEM_ACTIVE.replace(".", ""));

          $.each(
            $parentItem
              .parents("ul")
              .eq(0)
              .siblings("ul")
              .find("> li" + CHEESEBURGER_MENU_ITEM_ACTIVE),
            function () {
              $.each($(this).children("ul"), function () {
                animateToHeightAndClearStyle(
                  $(this),
                  0,
                  $(this).children("ul").length
                );
              });
            }
          );

          $.each(
            $parentItem.siblings(CHEESEBURGER_MENU_ITEM).not(DEFAULT_EXPAND),
            function () {
              $.each($(this).children("ul"), function () {
                animateToHeightAndClearStyle(
                  $(this),
                  0,
                  $(this).children("ul").length
                );
              });

              $(this).removeClass(
                CHEESEBURGER_MENU_ITEM_ACTIVE.replace(".", "")
              );
            }
          );

          $parentItem
            .parents("ul")
            .siblings("ul")
            .find(`> ${CHEESEBURGER_MENU_ITEM}`)
            .removeClass(CHEESEBURGER_MENU_ITEM_ACTIVE.replace(".", ""));

          // Animation.
          $.each($parentItem.children("ul"), function () {
            $(this).css({ height: "auto" });
            var height = $(this).height();
            $(this).css({ height: 0 });
            animateToHeightAndClearStyle(
              $(this),
              height,
              $parentItem.children("ul").length
            );
          });
        }
      }

      function animateToHeightAndClearStyle($el, height, coeficient) {
        var variation = 1;

        // Make longer menus animate slower.
        if (coeficient > 15) {
          variation += coeficient * 0.04;
        }

        $el.animate(
          { height: height },
          ANIMATION_SPEED * variation,
          function () {
            $(this).removeAttr("style");
          }
        );
      }

      function handleMiscClick(e) {
        if ($(e.target).parents(CHEESEBURGER_ACTIVE).length === 0) {
          $(CHEESEBURGER_ACTIVE).each(function () {
            $(this).removeClass(CHEESEBURGER_ACTIVE.replace(".", ""));
            $(this)
              .find(CHEESE_HIGHLIGHTED)
              .removeClass(CHEESE_HIGHLIGHTED.replace(".", ""));
            $(BACKDROP).removeClass(BACKDROP_ACTIVE.replace(".", ""));
            $("body").removeClass(BODY_ACTIVE.replace(".", ""));
          });
        }
      }

      function handleSideTriggerClick(e) {
        e.preventDefault();
        e.stopPropagation();

        var $targetId =
          $(e.target).attr(TRIGGER) ||
          $(e.target).parents(SIDE_TRIGGER).attr(TRIGGER);
        var $target = $(e.target)
          .parents(CHEESEBURGER)
          .find(CHEESEBURGER_MAINMENU)
          .find(`[${TRIGGER}=${$targetId}]`);
        $target.addClass(CHEESE_HIGHLIGHTED.replace(".", ""));
        var $offsetTopFromMainArea = $target.position().top;
        var $currentlyScrolled = $(e.target)
          .parents(CHEESEBURGER)
          .find(CHEESEBURGER_MAINMENU)
          .scrollTop();

        $(e.target)
          .parents(CHEESEBURGER)
          .eq(0)
          .find(CHEESEBURGER_MAINMENU)
          .animate(
            { scrollTop: $offsetTopFromMainArea + $currentlyScrolled },
            300
          );
      }

      function handleTitleTriggerClick(e) {
        e.preventDefault();
        e.stopPropagation();

        $(this)
          .parents(COLLAPSIBLE_TITLE)
          .toggleClass(EXPANDED_TITLE.replace(".", ""));

        $(this)
          .parents(COLLAPSIBLE_TITLE)
          .children(MAIN_MENU)
          .toggleClass(MENU_INVISIBLE)
          .toggleClass(MENU_VISIBLE);

        // if ( $(this).children(MAIN_MENU).hasClass(MENU_INVISIBLE)){
        //   console.log(MENU_INVISIBLE)
        // } else {
        //   console.log(MENU_VISIBLE)

        // }

        // $(this).children(MAIN_MENU).css({ height: "auto" });
        //     var height = $(this).height();
        //     $(this).children(MAIN_MENU).css({ height: 0 });
        //     animateToHeightAndClearStyle(
        //       $(this),
        //       height,
        //       $parentItem.children("ul").length
        //     );
      }

      function handleSideTriggerMouseUp(e) {
        var $targetId =
          $(e.target).attr(TRIGGER) ||
          $(e.target).parents(SIDE_TRIGGER).attr(TRIGGER);
        var $target = $(e.target)
          .parents(CHEESEBURGER)
          .find(CHEESEBURGER_MAINMENU)
          .find(`[${TRIGGER}=${$targetId}]`);
        $target.removeClass(CHEESE_HIGHLIGHTED.replace(".", ""));
      }

      function handleCheeseScroll() {
        var $scrollTop = $(CHEESEBURGER).scrollTop();
        $(CHEESEBURGER).find(CHEESEBURGER_SIDEMENU).css({ top: $scrollTop });
      }
    },
  };
})(Drupal, jQuery, _);
