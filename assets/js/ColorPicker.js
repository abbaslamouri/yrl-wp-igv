class ColorPicker {

  constructor(target, width, height) {

    this.target = target
    this.width = width
    this.height = height
    this.target.width = width
    this.target.height = height
    // this.x=50
    // this.y=50

    // Get canvas context
    this.context = this.target.getContext("2d")

    this.pickerCircle = { x: 0, y:0, width:7, height:7 }

    // this.build()

    this.target.addEventListener ("mousemove", (e)=>{
      this.build( e.offsetX,e.offsetY) 
    })

    this.target.addEventListener ("mousedown", (e)=>{
      console.log( e.offsetX,e.offsetY) 
      var pixel = this.context.getImageData( e.offsetX, e.offsetY, 1, 1)
      const rgba = `rgba(${pixel.data[0]}, ${pixel.data[1]}, ${pixel.data[2]}, ${pixel.data[3] / 255})`
      document.getElementsByClassName("selected")[0].style.backgroundColor = rgba
      console.log()
    })

  }

  draw() {



  }

  build(x =0, y=0) {

    // console.log(x, y)

    this.context.clearRect(0,0,this.width, this.height)


    // Create a linear gradient ( start gradient point x=20, y=0, end gradient point is at x=220, y=0 )
    let gradient = this.context.createLinearGradient(0, 0, this.width, 0)

    // Add color stops
    gradient.addColorStop( 0.00, 'rgb( 255, 0, 0 )' )
    gradient.addColorStop( 0.15, 'rgb( 255, 0, 255 )' )
    gradient.addColorStop( 0.33, 'rgb( 0, 0, 255 )' )
    gradient.addColorStop( 0.49, 'rgb( 0, 255, 255 )' )
    gradient.addColorStop( 0.67, 'rgb( 0, 255, 0 )' )
    gradient.addColorStop( 0.84, 'rgb( 255, 255, 0 )' )
    gradient.addColorStop( 1.00, 'rgb( 255, 0, 0 )' )

     // Set the fill style and draw a rectangle
     this.context.fillStyle = gradient;
     this.context.fillRect(0, 0, this.width, this.height);
 

    // Create black and white
    gradient = this.context.createLinearGradient(0, 0, 0, this.height)
    gradient.addColorStop( 0.00, 'rgba( 255, 255, 255, 1 )' )
    gradient.addColorStop( 0.50, 'rgba( 255, 255, 255, 0 )' )
    gradient.addColorStop( 0.50, 'rgba( 0, 0, 0, 0 )' )
    gradient.addColorStop( 1.00, 'rgba( 0, 0, 0, 1 )' )

    // Set the fill style and draw a rectangle
    this.context.fillStyle = gradient;
    this.context.fillRect(0, 0, this.width, this.height);
    // Create circle selector
    this.context.beginPath()
    this.context.arc (x, y, this.pickerCircle.width, 0, 2*Math.PI)
    this.context.strokeStyle = ( y > 2*this.height/3)? "white" : "black"
    this.context.stroke()
    // this.context.closePath()

  }

  // listenForEvents() {

  //   let isMouseDown = false

  //   const onMouseDown = (e) => {

  //   }

  //   const onMouseMove = (e) => {
      
  //   }

  //   const onMouseUp = (e) =>{
      
  //     isMouseDown = false

  //   }

  //   this.target.addEventListener("mousedown", onMouseDown)
  //   this.target.addEventListener("mousemove", onMouseMove)
  //   document.addEventListener("mouseup", onMouseUp)

  // }

}

const xx =new ColorPicker(document.getElementById("color-picker"), 400, 200)
 xx.build() 


// setInterval(xx.build(), 10)
setInterval(function(){ 
  // const xx =new ColorPicker(document.getElementById("color-picker"), 400, 200)

  // xx.build() 
}, 1000);

export default ColorPicker