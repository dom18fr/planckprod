if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}
var shadowAgenda = document.querySelector('[data-agenda="wrapper"]').cloneNode(true);
var selector = document.querySelector('[data-agenda=band-selector]');
if (null !== selector) {
    selector.addEventListener('change', function (e) {
        var agenda = document.querySelector('[data-agenda="wrapper"]');
        var parent = document.querySelector('.views-element-container');
        parent.removeChild(agenda);
        var newAgenda = shadowAgenda.cloneNode(true);
        if ('all' !== e.target.value) {
            newAgenda.querySelectorAll('.plkTeaserEvent').forEach(function (event) {
                if (e.target.value !== event.getAttribute('data-group')) {
                    event.remove();
                }
            });
            newAgenda.querySelectorAll('.plkAgendaYear').forEach(function (year) {
                if (null === year.querySelector('.plkTeaserEvent')) {
                    year.remove();
                }
            });
        }
        parent.appendChild(newAgenda);
        new ML.Tabs("[data-agenda=wrapper]");
    });
}
new ML.Tabs("[data-agenda=wrapper]");
