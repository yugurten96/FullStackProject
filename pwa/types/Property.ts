export class Property {
  public "@id"?: string;

  constructor(
    _id?: string,
    public region?: string,
    public surface?: number,
    public price?: number,
    public day?: string,
    public month?: string,
    public year?: string,
    public count?: number,
    public date?: string
  ) {
    this["@id"] = _id;
  }
}
