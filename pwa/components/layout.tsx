const Layout = ({title, main}) => {
  return (
    <div>
      <main>{title}</main>

      <body>
      <section className="hero is-info">
        <div className="hero-body">
          <div className="container has-text-centered">
            <p className="title">
              API Platform project
            </p>
          </div>
        </div>

        <div className="hero-foot">
          <nav className="tabs is-boxed is-fullwidth">
            <div className="container">
              <ul>
                <li>
                      <span className="icon-text">
                        <a href={"../"}>
                          <span className="icon"><i className="fas fa-home"/></span>
                          <span>Home</span></a>
                      </span>
                </li>
                <li>
                      <span className="icon-text">
                        <a href={"../admin#/"}>
                          <span className="icon"><i className="fas fa-unlock-alt"/></span>
                          <span>Admin</span></a>
                      </span>
                </li>
                <li>
                      <span className="icon-text">
                        <a href={"../properties/"}>
                          <span className="icon"><i className="fas fa-clipboard-list"/></span>
                          <span>Properties</span></a>
                      </span>
                </li>
                <li>
                      <span className="icon-text">
                        <a href={"../timeSeries/"}>
                          <span className="icon"><i className="fas fa-chart-line"/></span>
                          <span>TimeSeries</span></a>
                      </span>
                </li>
                <li>
                      <span className="icon-text">
                        <a href={"../barChart/"}>
                          <span className="icon"><i className="fas fa-chart-bar"/></span>
                          <span>BarChart</span></a>
                      </span>
                </li>
                <li>
                      <span className="icon-text">
                        <a href={"../circularDiagram/"}>
                          <span className="icon"><i className="fas fa-chart-pie"/></span>
                          <span>CircularDiagram</span></a>
                      </span>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </section>

      <section>
        <div className="columns">
          <div className="column is-half is-offset-one-quarter">
            <div className="box is-shadowless">
              <main>{main}</main>
            </div>
          </div>
        </div>
      </section>


      <footer className="footer">
        <div className="content has-text-centered">
            <span className="icon-text">
              <span className="icon"><i className="far fa-copyright"/></span>
              <span>By BOURGEAUX Maxence, GUYOMAR Robin, TAOUALIT Madjid & MERZOUK Yugurten</span>
            </span>
        </div>
      </footer>
      </body>
    </div>
  )
}

export default Layout;
