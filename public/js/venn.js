/*
 Highcharts JS v8.2.0 (2020-08-20)

 (c) 2017-2019 Highsoft AS
 Authors: Jon Arild Nygard

 License: www.highcharts.com/license
*/
(function(b) {
    "object" === typeof module && module.exports ? (b["default"] = b, module.exports = b) : "function" === typeof define && define.amd ? define("highcharts/modules/venn", ["highcharts"], function(m) {
        b(m);
        b.Highcharts = m;
        return b
    }) : b("undefined" !== typeof Highcharts ? Highcharts : void 0)
})(function(b) {
    function m(g, f, a, b) { g.hasOwnProperty(f) || (g[f] = b.apply(null, a)) }
    b = b ? b._modules : {};
    m(b, "Mixins/DrawPoint.js", [], function() {
        var b = function(a) { return "function" === typeof a },
            f = function(a) {
                var f, g = this,
                    d = g.graphic,
                    t = a.animatableAttribs,
                    u = a.onComplete,
                    m = a.css,
                    p = a.renderer,
                    r = null === (f = g.series) || void 0 === f ? void 0 : f.options.animation;
                if (g.shouldDraw()) d || (g.graphic = d = p[a.shapeType](a.shapeArgs).add(a.group)), d.css(m).attr(a.attribs).animate(t, a.isNew ? !1 : r, u);
                else if (d) {
                    var n = function() {
                        g.graphic = d = d.destroy();
                        b(u) && u()
                    };
                    Object.keys(t).length ? d.animate(t, void 0, function() { n() }) : n()
                }
            };
        return {
            draw: f,
            drawPoint: function(a) {
                (a.attribs = a.attribs || {})["class"] = this.getClassName();
                f.call(this, a)
            },
            isFn: b
        }
    });
    m(b, "Mixins/Geometry.js", [], function() {
        return {
            getAngleBetweenPoints: function(b,
                f) { return Math.atan2(f.x - b.x, f.y - b.y) },
            getCenterOfPoints: function(b) {
                var f = b.reduce(function(a, f) {
                    a.x += f.x;
                    a.y += f.y;
                    return a
                }, { x: 0, y: 0 });
                return { x: f.x / b.length, y: f.y / b.length }
            },
            getDistanceBetweenPoints: function(b, f) { return Math.sqrt(Math.pow(f.x - b.x, 2) + Math.pow(f.y - b.y, 2)) }
        }
    });
    m(b, "Mixins/GeometryCircles.js", [b["Mixins/Geometry.js"]], function(b) {
        function f(c, h) { h = Math.pow(10, h); return Math.round(c * h) / h }

        function a(c) {
            if (0 >= c) throw Error("radius of circle must be a positive number.");
            return Math.PI *
                c * c
        }

        function g(c, h) { return c * c * Math.acos(1 - h / c) - (c - h) * Math.sqrt(h * (2 * c - h)) }

        function m(c, h) {
            var a = n(c, h),
                b = c.r,
                d = h.r,
                y = [];
            if (a < b + d && a > Math.abs(b - d)) {
                b *= b;
                var w = (b - d * d + a * a) / (2 * a);
                d = Math.sqrt(b - w * w);
                b = c.x;
                y = h.x;
                c = c.y;
                var g = h.y;
                h = b + w * (y - b) / a;
                w = c + w * (g - c) / a;
                c = d / a * -(g - c);
                a = d / a * -(y - b);
                y = [{ x: f(h + c, 14), y: f(w - a, 14) }, { x: f(h - c, 14), y: f(w + a, 14) }]
            }
            return y
        }

        function d(c) {
            return c.reduce(function(c, a, b, f) {
                f = f.slice(b + 1).reduce(function(c, h, f) { var d = [b, f + b + 1]; return c.concat(m(a, h).map(function(c) { c.indexes = d; return c })) }, []);
                return c.concat(f)
            }, [])
        }

        function t(c, a) { return n(c, a) <= a.r + 1e-10 }

        function u(c, a) { return !a.some(function(a) { return !t(c, a) }) }

        function x(a) { return d(a).filter(function(c) { return u(c, a) }) }
        var p = b.getAngleBetweenPoints,
            r = b.getCenterOfPoints,
            n = b.getDistanceBetweenPoints;
        return {
            getAreaOfCircle: a,
            getAreaOfIntersectionBetweenCircles: function(a) {
                var c = x(a);
                if (1 < c.length) {
                    var b = r(c);
                    c = c.map(function(c) { c.angle = p(b, c); return c }).sort(function(c, a) { return a.angle - c.angle });
                    var f = c[c.length - 1];
                    c = c.reduce(function(c,
                        b) {
                        var h = c.startPoint,
                            f = r([h, b]),
                            d = b.indexes.filter(function(c) { return -1 < h.indexes.indexOf(c) }).reduce(function(c, d) {
                                d = a[d];
                                var q = p(d, b),
                                    g = p(d, h);
                                q = g - (g - q + (g < q ? 2 * Math.PI : 0)) / 2;
                                q = n(f, { x: d.x + d.r * Math.sin(q), y: d.y + d.r * Math.cos(q) });
                                d = d.r;
                                q > 2 * d && (q = 2 * d);
                                if (!c || c.width > q) c = { r: d, largeArc: q > d ? 1 : 0, width: q, x: b.x, y: b.y };
                                return c
                            }, null);
                        if (d) {
                            var q = d.r;
                            c.arcs.push(["A", q, q, 0, d.largeArc, 1, d.x, d.y]);
                            c.startPoint = b
                        }
                        return c
                    }, { startPoint: f, arcs: [] }).arcs;
                    if (0 !== c.length && 1 !== c.length) {
                        c.unshift(["M", f.x, f.y]);
                        var d = { center: b, d: c }
                    }
                }
                return d
            },
            getCircleCircleIntersection: m,
            getCirclesIntersectionPoints: d,
            getCirclesIntersectionPolygon: x,
            getCircularSegmentArea: g,
            getOverlapBetweenCircles: function(c, b, d) {
                var h = 0;
                d < c + b && (d <= Math.abs(b - c) ? h = a(c < b ? c : b) : (h = (c * c - b * b + d * d) / (2 * d), d -= h, h = g(c, c - h) + g(b, b - d)), h = f(h, 14));
                return h
            },
            isCircle1CompletelyOverlappingCircle2: function(c, a) { return n(c, a) + a.r < c.r + 1e-10 },
            isPointInsideCircle: t,
            isPointInsideAllCircles: u,
            isPointOutsideAllCircles: function(c, a) {
                return !a.some(function(a) {
                    return t(c,
                        a)
                })
            },
            round: f
        }
    });
    m(b, "Mixins/NelderMead.js", [], function() {
        var b = function(b) { b = b.slice(0, -1); for (var a = b.length, f = [], g = function(a, b) { a.sum += b[a.i]; return a }, d = 0; d < a; d++) f[d] = b.reduce(g, { sum: 0, i: d }).sum / a; return f };
        return {
            getCentroid: b,
            nelderMead: function(f, a) {
                var g = function(c, a) { return c.fx - a.fx },
                    m = function(c, a, b, d) { return a.map(function(a, h) { return c * a + b * d[h] }) },
                    d = function(c, a) {
                        a.fx = f(a);
                        c[c.length - 1] = a;
                        return c
                    },
                    t = function(a) {
                        var c = a[0];
                        return a.map(function(a) {
                            a = m(.5, c, .5, a);
                            a.fx = f(a);
                            return a
                        })
                    },
                    u = function(a, b, d, g) {
                        a = m(d, a, g, b);
                        a.fx = f(a);
                        return a
                    };
                a = function(a) {
                    var c = a.length,
                        b = Array(c + 1);
                    b[0] = a;
                    b[0].fx = f(a);
                    for (var d = 0; d < c; ++d) {
                        var g = a.slice();
                        g[d] = g[d] ? 1.05 * g[d] : .001;
                        g.fx = f(g);
                        b[d + 1] = g
                    }
                    return b
                }(a);
                for (var x = 0; 100 > x; x++) {
                    a.sort(g);
                    var p = a[a.length - 1],
                        r = b(a),
                        n = u(r, p, 2, -1);
                    n.fx < a[0].fx ? (p = u(r, p, 3, -2), a = d(a, p.fx < n.fx ? p : n)) : n.fx >= a[a.length - 2].fx ? n.fx > p.fx ? (r = u(r, p, .5, .5), a = r.fx < p.fx ? d(a, r) : t(a)) : (r = u(r, p, 1.5, -.5), a = r.fx < n.fx ? d(a, r) : t(a)) : a = d(a, n)
                }
                return a[0]
            }
        }
    });
    m(b, "Series/VennSeries.js", [b["Core/Color.js"], b["Core/Globals.js"], b["Core/Utilities.js"], b["Mixins/DrawPoint.js"], b["Mixins/Geometry.js"], b["Mixins/GeometryCircles.js"], b["Mixins/NelderMead.js"]], function(b, f, a, m, A, d, t) {
        function g(a, c) {
            var b = a.sets,
                e = c.reduce(function(a, e) {
                    var c = -1 < b.indexOf(e.sets[0]);
                    a[c ? "internal" : "external"].push(e.circle);
                    return a
                }, { internal: [], external: [] });
            e.external = e.external.filter(function(a) { return e.internal.some(function(e) { return !X(a, e) }) });
            a = Z(e.internal, e.external);
            c = J(a, e.internal, e.external);
            return { position: a, width: c }
        }

        function x(a) {
            var c = {},
                e = {};
            if (0 < a.length) {
                var b = K(a),
                    v = a.filter(z);
                a.forEach(function(a) {
                    var d = a.sets,
                        l = d.join();
                    if (d = z(a) ? b[l] : w(d.map(function(a) { return b[a] }))) c[l] = d, e[l] = g(a, v)
                })
            }
            return { mapOfIdToShape: c, mapOfIdToLabelValues: e }
        }
        var p = b.parse;
        b = a.addEvent;
        var r = a.animObject,
            n = a.extend,
            c = a.isArray,
            h = a.isNumber,
            q = a.isObject,
            S = a.isString,
            F = a.merge;
        a = a.seriesType;
        var y = d.getAreaOfCircle,
            w = d.getAreaOfIntersectionBetweenCircles,
            T = d.getCircleCircleIntersection,
            U = d.getCirclesIntersectionPolygon,
            G = d.getOverlapBetweenCircles,
            X = d.isCircle1CompletelyOverlappingCircle2,
            H = d.isPointInsideAllCircles,
            V = d.isPointInsideCircle,
            I = d.isPointOutsideAllCircles,
            W = t.nelderMead,
            aa = A.getCenterOfPoints,
            C = A.getDistanceBetweenPoints,
            L = f.seriesTypes,
            ba = function(a) { return Object.keys(a).map(function(c) { return a[c] }) },
            ca = function(a) {
                var c = 0;
                2 === a.length && (c = a[0], a = a[1], c = G(c.r, a.r, C(c, a)));
                return c
            },
            M = function(a, c) {
                return c.reduce(function(c, b) {
                    var e = 0;
                    1 < b.sets.length && (e = b.value, b = ca(b.sets.map(function(c) { return a[c] })),
                        b = e - b, e = Math.round(b * b * 1E11) / 1E11);
                    return c + e
                }, 0)
            },
            N = function(a, c, b, d, v) {
                var e = a(c),
                    l = a(b);
                v = v || 100;
                d = d || 1e-10;
                var k = b - c,
                    Y = 1;
                if (c >= b) throw Error("a must be smaller than b.");
                if (0 < e * l) throw Error("f(a) and f(b) must have opposite signs.");
                if (0 === e) var f = c;
                else if (0 === l) f = b;
                else
                    for (; Y++ <= v && 0 !== h && k > d;) {
                        k = (b - c) / 2;
                        f = c + k;
                        var h = a(f);
                        0 < e * h ? c = f : b = f
                    }
                return f
            },
            D = function(a, c, b) { var e = a + c; return 0 >= b ? e : y(a < c ? a : c) <= b ? 0 : N(function(e) { e = G(a, c, e); return b - e }, 0, e) },
            z = function(a) { return c(a.sets) && 1 === a.sets.length },
            E = function(a, c, b) { c = c.reduce(function(c, b) { b = b.r - C(a, b); return b <= c ? b : c }, Number.MAX_VALUE); return c = b.reduce(function(c, b) { b = C(a, b) - b.r; return b <= c ? b : c }, c) },
            Z = function(a, c) {
                var b = a.reduce(function(b, d) {
                    var e = d.r / 2;
                    return [{ x: d.x, y: d.y }, { x: d.x + e, y: d.y }, { x: d.x - e, y: d.y }, { x: d.x, y: d.y + e }, { x: d.x, y: d.y - e }].reduce(function(b, d) {
                        var e = E(d, a, c);
                        b.margin < e && (b.point = d, b.margin = e);
                        return b
                    }, b)
                }, { point: void 0, margin: -Number.MAX_VALUE }).point;
                b = W(function(b) { return -E({ x: b[0], y: b[1] }, a, c) }, [b.x, b.y]);
                b = { x: b[0], y: b[1] };
                H(b, a) && I(b, c) || (b = 1 < a.length ? aa(U(a)) : { x: a[0].x, y: a[0].y });
                return b
            },
            J = function(a, b, c) {
                var d = b.reduce(function(a, b) { return Math.min(b.r, a) }, Infinity),
                    e = c.filter(function(b) { return !V(a, b) });
                c = function(c, d) {
                    return N(function(f) {
                        var l = { x: a.x + d * f, y: a.y };
                        l = H(l, b) && I(l, e);
                        return -(c - f) + (l ? 0 : Number.MAX_VALUE)
                    }, 0, c)
                };
                return 2 * Math.min(c(d, -1), c(d, 1))
            },
            O = function(a) {
                var b = a.filter(function(a) { return 2 === a.sets.length }).reduce(function(a, b) {
                    b.sets.forEach(function(c, d, e) {
                        q(a[c]) || (a[c] = { overlapping: {}, totalOverlap: 0 });
                        a[c].totalOverlap += b.value;
                        a[c].overlapping[e[1 - d]] = b.value
                    });
                    return a
                }, {});
                a.filter(z).forEach(function(a) { n(a, b[a.sets[0]]) });
                return a
            },
            P = function(a, b) { return b.totalOverlap - a.totalOverlap },
            K = function(a) {
                var b = [],
                    c = {};
                a.filter(function(a) { return 1 === a.sets.length }).forEach(function(a) { c[a.sets[0]] = a.circle = { x: Number.MAX_VALUE, y: Number.MAX_VALUE, r: Math.sqrt(a.value / Math.PI) } });
                var d = function(a, c) {
                    var d = a.circle;
                    d.x = c.x;
                    d.y = c.y;
                    b.push(a)
                };
                O(a);
                var e = a.filter(z).sort(P);
                d(e.shift(), { x: 0, y: 0 });
                var f =
                    a.filter(function(a) { return 2 === a.sets.length });
                e.forEach(function(a) {
                    var e = a.circle,
                        v = e.r,
                        l = a.overlapping,
                        h = b.reduce(function(a, d, h) {
                            var k = d.circle,
                                g = D(v, k.r, l[d.sets[0]]),
                                B = [{ x: k.x + g, y: k.y }, { x: k.x - g, y: k.y }, { x: k.x, y: k.y + g }, { x: k.x, y: k.y - g }];
                            b.slice(h + 1).forEach(function(a) {
                                var b = a.circle;
                                a = D(v, b.r, l[a.sets[0]]);
                                B = B.concat(T({ x: k.x, y: k.y, r: g }, { x: b.x, y: b.y, r: a }))
                            });
                            B.forEach(function(b) {
                                e.x = b.x;
                                e.y = b.y;
                                var d = M(c, f);
                                d < a.loss && (a.loss = d, a.coordinates = b)
                            });
                            return a
                        }, { loss: Number.MAX_VALUE, coordinates: void 0 });
                    d(a, h.coordinates)
                });
                return c
            },
            Q = function(a) { var b = {}; return q(a) && h(a.value) && -1 < a.value && c(a.sets) && 0 < a.sets.length && !a.sets.some(function(a) { var c = !1;!b[a] && S(a) ? b[a] = !0 : c = !0; return c }) },
            R = function(a) {
                a = c(a) ? a : [];
                var b = a.reduce(function(a, b) { Q(b) && z(b) && 0 < b.value && -1 === a.indexOf(b.sets[0]) && a.push(b.sets[0]); return a }, []).sort(),
                    d = a.reduce(function(a, c) { Q(c) && !c.sets.some(function(a) { return -1 === b.indexOf(a) }) && (a[c.sets.sort().join()] = c); return a }, {});
                b.reduce(function(a, b, c, d) {
                    d.slice(c + 1).forEach(function(c) {
                        a.push(b +
                            "," + c)
                    });
                    return a
                }, []).forEach(function(a) {
                    if (!d[a]) {
                        var b = { sets: a.split(","), value: 0 };
                        d[a] = b
                    }
                });
                return ba(d)
            },
            da = function(a, b, c) {
                var d = c.bottom - c.top,
                    e = c.right - c.left;
                d = Math.min(0 < e ? 1 / e * a : 1, 0 < d ? 1 / d * b : 1);
                return { scale: d, centerX: a / 2 - (c.right + c.left) / 2 * d, centerY: b / 2 - (c.top + c.bottom) / 2 * d }
            };
        a("venn", "scatter", {
            borderColor: "#cccccc",
            borderDashStyle: "solid",
            borderWidth: 1,
            brighten: 0,
            clip: !1,
            colorByPoint: !0,
            dataLabels: { enabled: !0, verticalAlign: "middle", formatter: function() { return this.point.name } },
            inactiveOtherPoints: !0,
            marker: !1,
            opacity: .75,
            showInLegend: !1,
            states: { hover: { opacity: 1, borderColor: "#333333" }, select: { color: "#cccccc", borderColor: "#000000", animation: !1 }, inactive: { opacity: .075 } },
            tooltip: { pointFormat: "{point.name}: {point.value}" }
        }, {
            isCartesian: !1,
            axisTypes: [],
            directTouch: !0,
            pointArrayMap: ["value"],
            init: function() {
                L.scatter.prototype.init.apply(this, arguments);
                delete this.opacity
            },
            translate: function() {
                var a = this.chart;
                this.processedXData = this.xData;
                this.generatePoints();
                var b = R(this.options.data);
                b = x(b);
                var d = b.mapOfIdToShape,
                    f = b.mapOfIdToLabelValues;
                b = Object.keys(d).filter(function(a) { return (a = d[a]) && h(a.r) }).reduce(function(a, b) {
                    var c = d[b];
                    b = c.x - c.r;
                    var e = c.x + c.r,
                        f = c.y + c.r;
                    c = c.y - c.r;
                    if (!h(a.left) || a.left > b) a.left = b;
                    if (!h(a.right) || a.right < e) a.right = e;
                    if (!h(a.top) || a.top > c) a.top = c;
                    if (!h(a.bottom) || a.bottom < f) a.bottom = f;
                    return a
                }, { top: 0, bottom: 0, left: 0, right: 0 });
                a = da(a.plotWidth, a.plotHeight, b);
                var g = a.scale,
                    m = a.centerX,
                    n = a.centerY;
                this.points.forEach(function(a) {
                    var b = c(a.sets) ? a.sets : [],
                        e = b.join(),
                        l = d[e],
                        k = f[e] || {};
                    e = k.width;
                    k = k.position;
                    var v = a.options && a.options.dataLabels;
                    if (l) {
                        if (l.r) var p = { x: m + l.x * g, y: n + l.y * g, r: l.r * g };
                        else l.d && (l = l.d, l.forEach(function(a) { "M" === a[0] ? (a[1] = m + a[1] * g, a[2] = n + a[2] * g) : "A" === a[0] && (a[1] *= g, a[2] *= g, a[6] = m + a[6] * g, a[7] = n + a[7] * g) }), p = { d: l });
                        k ? (k.x = m + k.x * g, k.y = n + k.y * g) : k = {};
                        h(e) && (e = Math.round(e * g))
                    }
                    a.shapeArgs = p;
                    k && p && (a.plotX = k.x, a.plotY = k.y);
                    e && p && (a.dlOptions = F(!0, { style: { width: e } }, q(v) && v));
                    a.name = a.options.name || b.join("\u2229")
                })
            },
            drawPoints: function() {
                var a =
                    this,
                    b = a.chart,
                    d = a.group,
                    f = b.renderer;
                (a.points || []).forEach(function(e) {
                    var g = { zIndex: c(e.sets) ? e.sets.length : 0 },
                        h = e.shapeArgs;
                    b.styledMode || n(g, a.pointAttribs(e, e.state));
                    e.draw({ isNew: !e.graphic, animatableAttribs: h, attribs: g, group: d, renderer: f, shapeType: h && h.d ? "path" : "circle" })
                })
            },
            pointAttribs: function(a, b) {
                var c = this.options || {};
                a = F(c, { color: a && a.color }, a && a.options || {}, b && c.states[b] || {});
                return {
                    fill: p(a.color).setOpacity(a.opacity).brighten(a.brightness).get(),
                    stroke: a.borderColor,
                    "stroke-width": a.borderWidth,
                    dashstyle: a.borderDashStyle
                }
            },
            animate: function(a) {
                if (!a) {
                    var b = r(this.options.animation);
                    this.points.forEach(function(a) {
                        var c = a.shapeArgs;
                        if (a.graphic && c) {
                            var d = {},
                                e = {};
                            c.d ? d.opacity = .001 : (d.r = 0, e.r = c.r);
                            a.graphic.attr(d).animate(e, b);
                            c.d && setTimeout(function() { a && a.graphic && a.graphic.animate({ opacity: 1 }) }, b.duration)
                        }
                    }, this)
                }
            },
            utils: {
                addOverlapToSets: O,
                geometry: A,
                geometryCircles: d,
                getLabelWidth: J,
                getMarginFromCircles: E,
                getDistanceBetweenCirclesByOverlap: D,
                layoutGreedyVenn: K,
                loss: M,
                nelderMead: t,
                processVennData: R,
                sortByTotalOverlap: P
            }
        }, { draw: m.draw, shouldDraw: function() { return !!this.shapeArgs }, isValid: function() { return h(this.value) } });
        b(L.venn, "afterSetOptions", function(a) {
            var b = a.options.states;
            this.is("venn") && Object.keys(b).forEach(function(a) { b[a].halo = !1 })
        })
    });
    m(b, "masters/modules/venn.src.js", [], function() {})
});
//# sourceMappingURL=venn.js.map