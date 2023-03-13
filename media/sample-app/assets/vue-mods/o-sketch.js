
let mixin_store = await import('./mixin-store.js');
let mixins = [mixin_store.default];


let template = `
<div class="compo">
    <h3>Sketch</h3>
    <div class="sketch" ref="sketch_box">
    </div>
</div>
`

export default {
    template,
    mixins,
    data() {
        return {
            mysketch: null,
            myp5: null
        }
    },
    mounted() {
        // path relative to web page
        let script_js = [{
            'name': 'p5',
            'url': 'assets/p5/p5.min.js'
        }]

        this.load_js_order(script_js, 0, this.compo_init)
    },
    methods: {
        compo_init: function () {
            // https://p5js.org/reference/#/p5/p5
            // add a new P5 sketch instance to the sketch_box element
            let sketch_box = this.$refs.sketch_box;
            // get sketch_box width and height
            let width = sketch_box.offsetWidth;
            // JS bug: sketch_box.offsetHeight is 0
            let height = sketch_box.offsetHeight;

            this.mysketch = function (p) {
                let x = 100;
                let y = 100;

                p.setup = function () {
                    console.log('setup', width, height)
                    p.createCanvas(width, width);
                };
                p.draw = function () {
                    p.background(220);
                    p.fill(255);
                    p.rect(x, y, 50, 50);
                    p.fill(0);
                    p.ellipse(p.mouseX, p.mouseY, 100, 100);

                };

                p.windowResized = function () {
                    let width = sketch_box.offsetWidth;
                    console.log('windowResized', width);
                    p.resizeCanvas(width, width);
                }
            };
            this.myp5 = new p5(this.mysketch, sketch_box);
        }

    }
}