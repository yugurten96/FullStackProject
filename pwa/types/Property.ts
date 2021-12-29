export class Property {
  public "@id"?: string;

  constructor(
    _id?: string,
    public region?: string,
    public surface?: number,
    public price?: number,
    public sellDay?: string,
    public sellMonth?: string,
    public sellYear?: string,
    public count?: number,
    public sellDate?: string
  ) {
    this["@id"] = _id;
  }
}
